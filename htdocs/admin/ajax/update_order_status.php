<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../lib/database.php";
require ('../Carbon/autoload.php');
use Carbon\Carbon;

class UpdateOrderStatus {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function update_order($session_idA, $status) {
        $session_idA = mysqli_real_escape_string($this->db->link, $session_idA);
        $status = mysqli_real_escape_string($this->db->link, $status);

        if (empty($session_idA) || ($status !== '0' && $status !== '1')) {
            return json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
        }

        $query_check = "SELECT statusA FROM tbl_payment WHERE session_idA = ?";
        $stmt_check = $this->db->link->prepare($query_check);
        $stmt_check->bind_param("s", $session_idA);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows === 0) {
            $stmt_check->close();
            return json_encode(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng']);
        }
        $current_status = $result_check->fetch_assoc()['statusA'];
        $stmt_check->close();

        $query_cart = "SELECT tbl_carta.quantitys, tbl_sanpham.sanpham_gia 
                       FROM tbl_carta 
                       JOIN tbl_sanpham ON tbl_carta.sanpham_id = tbl_sanpham.sanpham_id 
                       WHERE tbl_carta.session_idA = ?";
        $stmt_cart = $this->db->link->prepare($query_cart);
        $stmt_cart->bind_param("s", $session_idA);
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();

        $soluong = 0;
        $doanhthu = 0;
        while ($row = $result_cart->fetch_assoc()) {
            $soluong += $row['quantitys'];
            $doanhthu += $row['sanpham_gia'] * $row['quantitys'];
        }
        $stmt_cart->close();

        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        if ($current_status == '1' && $status == '0') {
            $query_thongke = "SELECT * FROM tbl_thongke WHERE date_thongke = ?";
            $stmt_thongke = $this->db->link->prepare($query_thongke);
            $stmt_thongke->bind_param("s", $now);
            $stmt_thongke->execute();
            $result_thongke = $stmt_thongke->get_result();

            if ($result_thongke->num_rows > 0) {
                $row_tk = $result_thongke->fetch_assoc();
                $new_soluong = $row_tk['soluong'] - $soluong;
                $new_doanhthu = $row_tk['doanhthu'] - $doanhthu;
                $new_donhang = $row_tk['donhang'] - 1;

                if ($new_soluong < 0 || $new_doanhthu < 0 || $new_donhang < 0) {
                    $stmt_thongke->close();
                    return json_encode(['status' => 'error', 'message' => 'Dữ liệu thống kê không hợp lệ sau khi trừ']);
                }

                $query_update_thongke = "UPDATE tbl_thongke SET soluong = ?, doanhthu = ?, donhang = ? 
                                         WHERE date_thongke = ?";
                $stmt_update_thongke = $this->db->link->prepare($query_update_thongke);
                $stmt_update_thongke->bind_param("iiis", $new_soluong, $new_doanhthu, $new_donhang, $now);
                $result_update_thongke = $stmt_update_thongke->execute();
                $stmt_update_thongke->close();

                if (!$result_update_thongke) {
                    $stmt_thongke->close();
                    return json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật thống kê']);
                }
            } else {
                $stmt_thongke->close();
                return json_encode(['status' => 'error', 'message' => 'Không tìm thấy thống kê để trừ']);
            }
            $stmt_thongke->close();
        } elseif ($current_status == '0' && $status == '1') {
            $query_thongke = "SELECT * FROM tbl_thongke WHERE date_thongke = ?";
            $stmt_thongke = $this->db->link->prepare($query_thongke);
            $stmt_thongke->bind_param("s", $now);
            $stmt_thongke->execute();
            $result_thongke = $stmt_thongke->get_result();

            if ($result_thongke->num_rows == 0) {
                $donhang = 1;
                $query_insert = "INSERT INTO tbl_thongke (date_thongke, soluong, doanhthu, donhang) 
                                 VALUES (?, ?, ?, ?)";
                $stmt_insert = $this->db->link->prepare($query_insert);
                $stmt_insert->bind_param("siii", $now, $soluong, $doanhthu, $donhang);
                $result_insert = $stmt_insert->execute();
                $stmt_insert->close();

                if (!$result_insert) {
                    $stmt_thongke->close();
                    return json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm thống kê']);
                }
            } else {
                $row_tk = $result_thongke->fetch_assoc();
                $new_soluong = $row_tk['soluong'] + $soluong;
                $new_doanhthu = $row_tk['doanhthu'] + $doanhthu;
                $new_donhang = $row_tk['donhang'] + 1;

                $query_update_thongke = "UPDATE tbl_thongke SET soluong = ?, doanhthu = ?, donhang = ? 
                                         WHERE date_thongke = ?";
                $stmt_update_thongke = $this->db->link->prepare($query_update_thongke);
                $stmt_update_thongke->bind_param("iiis", $new_soluong, $new_doanhthu, $new_donhang, $now);
                $result_update_thongke = $stmt_update_thongke->execute();
                $stmt_update_thongke->close();

                if (!$result_update_thongke) {
                    $stmt_thongke->close();
                    return json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật thống kê']);
                }
            }
            $stmt_thongke->close();
        }

        $query = "UPDATE tbl_payment SET statusA = ? WHERE session_idA = ?";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("ss", $status, $session_idA);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return json_encode(['status' => 'success']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật trạng thái đơn hàng']);
        }
    }
}

header('Content-Type: application/json');

if (isset($_POST['session_idA']) && isset($_POST['status'])) {
    $session_idA = $_POST['session_idA'];
    $status = $_POST['status'];

    $updateOrder = new UpdateOrderStatus();
    echo $updateOrder->update_order($session_idA, $status);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu session_idA hoặc status']);
}
?>