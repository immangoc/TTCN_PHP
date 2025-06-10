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

        // Kiểm tra đơn hàng tồn tại
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

        // Lấy danh sách sản phẩm trong giỏ hàng
        $query_cart = "SELECT sanpham_id, quantitys FROM tbl_carta WHERE session_idA = ?";
        $stmt_cart = $this->db->link->prepare($query_cart);
        $stmt_cart->bind_param("s", $session_idA);
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();

        $soluong = 0;
        $doanhthu = 0;
        $cart_items = [];
        while ($row = $result_cart->fetch_assoc()) {
            $cart_items[] = $row;
            $soluong += $row['quantitys'];
            $query_price = "SELECT sanpham_gia FROM tbl_sanpham WHERE sanpham_id = ?";
            $stmt_price = $this->db->link->prepare($query_price);
            $stmt_price->bind_param("i", $row['sanpham_id']);
            $stmt_price->execute();
            $price_result = $stmt_price->get_result()->fetch_assoc();
            $doanhthu += $price_result['sanpham_gia'] * $row['quantitys'];
            $stmt_price->close();
        }
        $stmt_cart->close();

        // Bắt đầu transaction
        $this->db->link->begin_transaction();

        try {
            if ($current_status == '0' && $status == '1') {
                // Kiểm tra và trừ tồn kho
                foreach ($cart_items as $item) {
                    $sanpham_id = $item['sanpham_id'];
                    $quantity_ordered = $item['quantitys'];

                    // Khóa dòng sản phẩm để kiểm tra tồn kho
                    $query_stock = "SELECT soluong FROM tbl_sanpham WHERE sanpham_id = ? FOR UPDATE";
                    $stmt_stock = $this->db->link->prepare($query_stock);
                    $stmt_stock->bind_param("i", $sanpham_id);
                    $stmt_stock->execute();
                    $stock_result = $stmt_stock->get_result()->fetch_assoc();
                    $stmt_stock->close();

                    if ($stock_result['soluong'] < $quantity_ordered) {
                        throw new Exception('Số lượng trong kho không đủ cho sản phẩm ID: ' . $sanpham_id);
                    }

                    // Trừ số lượng trong kho
                    $query_update_stock = "UPDATE tbl_sanpham SET soluong = soluong - ? WHERE sanpham_id = ?";
                    $stmt_update_stock = $this->db->link->prepare($query_update_stock);
                    $stmt_update_stock->bind_param("ii", $quantity_ordered, $sanpham_id);
                    if (!$stmt_update_stock->execute()) {
                        throw new Exception('Lỗi khi cập nhật số lượng sản phẩm ID: ' . $sanpham_id);
                    }
                    $stmt_update_stock->close();
                }

                // Cập nhật thống kê
                $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
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
                    if (!$stmt_insert->execute()) {
                        throw new Exception('Lỗi khi thêm thống kê');
                    }
                    $stmt_insert->close();
                } else {
                    $row_tk = $result_thongke->fetch_assoc();
                    $new_soluong = $row_tk['soluong'] + $soluong;
                    $new_doanhthu = $row_tk['doanhthu'] + $doanhthu;
                    $new_donhang = $row_tk['donhang'] + 1;

                    $query_update_thongke = "UPDATE tbl_thongke SET soluong = ?, doanhthu = ?, donhang = ? 
                                             WHERE date_thongke = ?";
                    $stmt_update_thongke = $this->db->link->prepare($query_update_thongke);
                    $stmt_update_thongke->bind_param("iiis", $new_soluong, $new_doanhthu, $new_donhang, $now);
                    if (!$stmt_update_thongke->execute()) {
                        throw new Exception('Lỗi khi cập nhật thống kê');
                    }
                    $stmt_update_thongke->close();
                }
                $stmt_thongke->close();
            } elseif ($current_status == '1' && $status == '0') {
                // Hoàn lại số lượng trong kho
                foreach ($cart_items as $item) {
                    $sanpham_id = $item['sanpham_id'];
                    $quantity_ordered = $item['quantitys'];

                    // Khóa dòng sản phẩm
                    $query_stock = "SELECT soluong FROM tbl_sanpham WHERE sanpham_id = ? FOR UPDATE";
                    $stmt_stock = $this->db->link->prepare($query_stock);
                    $stmt_stock->bind_param("i", $sanpham_id);
                    $stmt_stock->execute();
                    $stmt_stock->close();

                    // Cộng lại số lượng
                    $query_update_stock = "UPDATE tbl_sanpham SET soluong = soluong + ? WHERE sanpham_id = ?";
                    $stmt_update_stock = $this->db->link->prepare($query_update_stock);
                    $stmt_update_stock->bind_param("ii", $quantity_ordered, $sanpham_id);
                    if (!$stmt_update_stock->execute()) {
                        throw new Exception('Lỗi khi hoàn lại số lượng sản phẩm ID: ' . $sanpham_id);
                    }
                    $stmt_update_stock->close();
                }

                // Cập nhật thống kê
                $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
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
                        throw new Exception('Dữ liệu thống kê không hợp lệ sau khi trừ');
                    }

                    $query_update_thongke = "UPDATE tbl_thongke SET soluong = ?, doanhthu = ?, donhang = ? 
                                             WHERE date_thongke = ?";
                    $stmt_update_thongke = $this->db->link->prepare($query_update_thongke);
                    $stmt_update_thongke->bind_param("iiis", $new_soluong, $new_doanhthu, $new_donhang, $now);
                    if (!$stmt_update_thongke->execute()) {
                        throw new Exception('Lỗi khi cập nhật thống kê');
                    }
                    $stmt_update_thongke->close();
                } else {
                    throw new Exception('Không tìm thấy thống kê để trừ');
                }
                $stmt_thongke->close();
            }

            // Cập nhật trạng thái đơn hàng
            $query = "UPDATE tbl_payment SET statusA = ? WHERE session_idA = ?";
            $stmt = $this->db->link->prepare($query);
            $stmt->bind_param("ss", $status, $session_idA);
            if (!$stmt->execute()) {
                throw new Exception('Lỗi khi cập nhật trạng thái đơn hàng');
            }
            $stmt->close();

            // Commit transaction
            $this->db->link->commit();
            return json_encode(['status' => 'success', 'message' => 'Cập nhật trạng thái và số lượng kho thành công']);
        } catch (Exception $e) {
            // Rollback transaction nếu có lỗi
            $this->db->link->rollback();
            return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
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