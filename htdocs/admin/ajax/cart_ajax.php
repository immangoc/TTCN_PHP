<?php
session_start();
include "../lib/database.php";

class Cart {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function add_to_cart($data) {
        $session_id = mysqli_real_escape_string($this->db->link, $data['session_id']);
        $sanpham_id = intval($data['sanpham_id']);
        $sanpham_tieude = mysqli_real_escape_string($this->db->link, $data['sanpham_tieude']);
        $sanpham_anh = mysqli_real_escape_string($this->db->link, $data['sanpham_anh']);
        $sanpham_gia = floatval($data['sanpham_gia']);
        $color_anh = mysqli_real_escape_string($this->db->link, $data['color_anh']);
        $quantitys = intval($data['quantitys']);
        $sanpham_size = mysqli_real_escape_string($this->db->link, $data['sanpham_size']);

        // Kiểm tra số lượng tồn kho
        $query_stock = "SELECT soluong FROM tbl_sanpham WHERE sanpham_id = ?";
        $stmt_stock = $this->db->link->prepare($query_stock);
        $stmt_stock->bind_param("i", $sanpham_id);
        $stmt_stock->execute();
        $stock_result = $stmt_stock->get_result()->fetch_assoc();
        $stmt_stock->close();

        if ($stock_result['soluong'] <= 0) {
            return json_encode(['status' => 'error', 'message' => 'Sản phẩm đã hết hàng!']);
        }
        if ($quantitys > $stock_result['soluong']) {
            return json_encode(['status' => 'error', 'message' => 'Số lượng đặt hàng vượt quá tồn kho!']);
        }

        // Thêm vào giỏ hàng
        $query = "INSERT INTO tbl_carta (session_id, sanpham_id, sanpham_tieude, sanpham_anh, sanpham_gia, color_anh, quantitys, sanpham_size)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("sisssdss", $session_id, $sanpham_id, $sanpham_tieude, $sanpham_anh, $sanpham_gia, $color_anh, $quantitys, $sanpham_size);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return json_encode(['status' => 'success', 'message' => 'Thêm vào giỏ hàng thành công']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm vào giỏ hàng']);
        }
    }
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = new Cart();
    echo $cart->add_to_cart($_POST);
}
?>