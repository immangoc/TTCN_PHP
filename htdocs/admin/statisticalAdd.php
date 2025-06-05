<?php
include "lib/database.php";
require ('Carbon/autoload.php');
use Carbon\Carbon;

$db = new Database();
$now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

if (isset($_GET['session_idA'])) {
    $session_idA = htmlspecialchars($_GET['session_idA'], ENT_QUOTES, 'UTF-8');

    // Sử dụng prepared statement để cập nhật trạng thái
    $sql_update = "UPDATE tbl_payment SET statusA = 1 WHERE session_idA = ?";
    $stmt_update = $db->link->prepare($sql_update); // Thay $conn thành $link
    $stmt_update->bind_param("s", $session_idA);
    $resultA = $stmt_update->execute();
    $stmt_update->close();

    if ($resultA) {
        // Truy vấn dữ liệu giỏ hàng
        $sql_query = "SELECT tbl_carta.quantitys, tbl_sanpham.sanpham_gia 
                      FROM tbl_carta 
                      JOIN tbl_sanpham ON tbl_carta.sanpham_id = tbl_sanpham.sanpham_id 
                      WHERE tbl_carta.session_idA = ?";
        $stmt_query = $db->link->prepare($sql_query); // Thay $conn thành $link
        $stmt_query->bind_param("s", $session_idA);
        $stmt_query->execute();
        $resultB = $stmt_query->get_result();

        $soluong = 0;
        $doanhthu = 0;

        // Tính tổng số lượng và doanh thu
        while ($row = $resultB->fetch_assoc()) {
            $soluong += $row['quantitys'];
            $doanhthu += $row['sanpham_gia'] * $row['quantitys'];
        }
        $stmt_query->close();

        // Kiểm tra bản ghi thống kê cho ngày hiện tại
        $sql_thongke = "SELECT * FROM tbl_thongke WHERE date_thongke = ?";
        $stmt_thongke = $db->link->prepare($sql_thongke); // Thay $conn thành $link
        $stmt_thongke->bind_param("s", $now);
        $stmt_thongke->execute();
        $resultC = $stmt_thongke->get_result();

        if ($resultC->num_rows == 0) {
            // Thêm bản ghi mới
            $donhang = 1;
            $sql_insert = "INSERT INTO tbl_thongke (date_thongke, soluong, doanhthu, donhang) 
                           VALUES (?, ?, ?, ?)";
            $stmt_insert = $db->link->prepare($sql_insert); // Thay $conn thành $link
            $stmt_insert->bind_param("siii", $now, $soluong, $doanhthu, $donhang);
            $resultD = $stmt_insert->execute();
            $stmt_insert->close();
        } else {
            // Cập nhật bản ghi hiện có
            $row_tk = $resultC->fetch_assoc();
            $soluong += $row_tk['soluong'];
            $doanhthu += $row_tk['doanhthu'];
            $donhang = $row_tk['donhang'] + 1;

            $sql_update_thongke = "UPDATE tbl_thongke SET soluong = ?, doanhthu = ?, donhang = ? 
                                   WHERE date_thongke = ?";
            $stmt_update_thongke = $db->link->prepare($sql_update_thongke); // Thay $conn thành $link
            $stmt_update_thongke->bind_param("iiis", $soluong, $doanhthu, $donhang, $now);
            $resultD = $stmt_update_thongke->execute();
            $stmt_update_thongke->close();
        }
        $stmt_thongke->close();

        // Chuyển hướng nếu thành công
        if ($resultD) {
            header('Location: orderlist.php');
            exit;
        } else {
            echo "Lỗi khi cập nhật thống kê.";
        }
    } else {
        echo "Lỗi khi cập nhật trạng thái đơn hàng.";
    }
} else {
    echo "Không tìm thấy session_idA.";
}
?>