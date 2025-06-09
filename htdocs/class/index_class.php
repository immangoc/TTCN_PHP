<?php
 define('__ROOT__', dirname(dirname(__FILE__))); 
 require_once(__ROOT__.'/lib/database.php');
 require_once(__ROOT__.'/lib/session.php');
 require_once(__ROOT__.'/helper/format.php');
 require_once(__ROOT__.'/mail/index.php');
//  require('../../mail/sendmail.php');
?>

<?php
 class index
 {

    private $db;
    private $fm;

    public function __construct()
    {
        $this ->db = new Database();
        $this ->fm = new Format();
    }
    //    ============ Hiện danh mục ================
    public function show_danhmuc(){
        $query = "SELECT * FROM tbl_danhmuc ORDER BY danhmuc_id";
        $result = $this -> db ->select($query);
        return $result;
    }

     //    ============ Hiện loại sản phẩm ================
    public function show_loaisanpham($danhmuc_id) {
        $query = "SELECT * FROM tbl_loaisanpham  WHERE danhmuc_id = '$danhmuc_id' ORDER BY loaisanpham_id";
        $result = $this -> db ->select($query);
        return $result;
    }

    //    =========== Hiện ra danh mục và loại sản phẩm === leftside.php ========
    public function get_loaisanphamA($loaisanpham_id){
        $query = "SELECT tbl_sanpham.*, tbl_danhmuc.danhmuc_ten,tbl_loaisanpham.loaisanpham_ten
        FROM tbl_sanpham INNER JOIN tbl_danhmuc ON tbl_sanpham.danhmuc_id = tbl_danhmuc.danhmuc_id
        INNER JOIN tbl_loaisanpham ON tbl_sanpham.loaisanpham_id = tbl_loaisanpham.loaisanpham_id
        WHERE tbl_sanpham.loaisanpham_id = '$loaisanpham_id'
        ORDER BY tbl_sanpham.sanpham_id DESC  ";
        $result = $this -> db ->select($query);
        return $result;
    }

    //    ========= Hiện ra sản phẩm ============ Subpage.php ===============
    public function get_loaisanpham($loaisanpham_id,$orderConditon){
        $sp_tungtrang = 10;
        if(!isset($_GET['trang'])){
            $trang = 1;
        }else {
            $trang = $_GET['trang'];
        }
        $trung_trang = ($trang - 1) * $sp_tungtrang;
        $query = "SELECT tbl_sanpham.*, tbl_danhmuc.danhmuc_ten,tbl_loaisanpham.loaisanpham_ten
        FROM tbl_sanpham INNER JOIN tbl_danhmuc ON tbl_sanpham.danhmuc_id = tbl_danhmuc.danhmuc_id
        INNER JOIN tbl_loaisanpham ON tbl_sanpham.loaisanpham_id = tbl_loaisanpham.loaisanpham_id
        WHERE tbl_sanpham.loaisanpham_id = '$loaisanpham_id'
        $orderConditon LIMIT $trung_trang, $sp_tungtrang ";
        $result = $this -> db ->select($query);
        return $result;
    }

     // ======== phân trang sản phẩm =======
     public function show_All_product($loaisanpham_id,$orderConditon){
        $query = "SELECT tbl_sanpham.*, tbl_danhmuc.danhmuc_ten,tbl_loaisanpham.loaisanpham_ten
        FROM tbl_sanpham INNER JOIN tbl_danhmuc ON tbl_sanpham.danhmuc_id = tbl_danhmuc.danhmuc_id
        INNER JOIN tbl_loaisanpham ON tbl_sanpham.loaisanpham_id = tbl_loaisanpham.loaisanpham_id
        WHERE tbl_sanpham.loaisanpham_id = '$loaisanpham_id'
        $orderConditon ";
        $result = $this -> db ->select($query);
        return $result;
    }

    // ========= Chi tiết SP ========== product.php ==========
    public function get_sanpham($sanpham_id) {
        $query = "SELECT tbl_sanpham.*, tbl_danhmuc.danhmuc_ten,tbl_loaisanpham.loaisanpham_ten,tbl_color.color_ten,tbl_color.color_anh
        FROM tbl_sanpham INNER JOIN tbl_danhmuc ON tbl_sanpham.danhmuc_id = tbl_danhmuc.danhmuc_id
        INNER JOIN tbl_loaisanpham ON tbl_sanpham.loaisanpham_id = tbl_loaisanpham.loaisanpham_id
        INNER JOIN tbl_color ON tbl_sanpham.color_id = tbl_color.color_id
        WHERE tbl_sanpham.sanpham_id = '$sanpham_id'
        ORDER BY tbl_sanpham.sanpham_id DESC  ";
        $result = $this -> db ->select($query);
        return $result;
    }

    // ======== sản phẩm index ===========
    public function show_product(){
        $query = "SELECT tbl_sanpham.*, tbl_danhmuc.danhmuc_ten,tbl_loaisanpham.loaisanpham_ten,tbl_color.color_ten
        FROM tbl_sanpham INNER JOIN tbl_danhmuc ON tbl_sanpham.danhmuc_id = tbl_danhmuc.danhmuc_id
        INNER JOIN tbl_loaisanpham ON tbl_sanpham.loaisanpham_id = tbl_loaisanpham.loaisanpham_id
        INNER JOIN tbl_color ON tbl_sanpham.color_id = tbl_color.color_id
        ORDER BY tbl_sanpham.sanpham_id DESC";
        $result = $this -> db ->select($query);
        return $result;
    }

    public function get_Subpage($loaisanpham_id){
        $query = "SELECT tbl_sanpham.*, tbl_danhmuc.danhmuc_ten,tbl_loaisanpham.loaisanpham_ten,tbl_color.color_ten
        FROM tbl_sanpham INNER JOIN tbl_danhmuc ON tbl_sanpham.danhmuc_id = tbl_danhmuc.danhmuc_id
        INNER JOIN tbl_loaisanpham ON tbl_sanpham.loaisanpham_id = tbl_loaisanpham.loaisanpham_id
        INNER JOIN tbl_color ON tbl_sanpham.color_id = tbl_color.color_id
        WHERE tbl_sanpham.loaisanpham_id = '$loaisanpham_id'
        ORDER BY tbl_sanpham.sanpham_id DESC  ";
        $result = $this -> db ->select($query);
        return $result;
    }

    // ========= Ảnh mô tả ===========
    public function get_anh($sanpham_id) {
        $query = "SELECT * FROM tbl_sanpham_anh WHERE sanpham_id = '$sanpham_id' ORDER BY sanpham_anh_id DESC";
        $result = $this -> db ->select($query);
        return $result;
    }

    //  ======= Size SP ==============
    public function get_size($sanpham_id) {
        $query = "SELECT * FROM tbl_sanpham_size WHERE sanpham_id = '$sanpham_id' ORDER BY sanpham_size_id DESC";
        $result = $this -> db ->select($query);
        return $result;
    }

    //   ======== Mua hàng ========= 
   public function insert_cart($sanpham_anh, $session_idA, $sanpham_id, $sanpham_tieude, $sanpham_gia, $color_anh, $quantitys, $sanpham_size) {
    $query = "INSERT INTO tbl_cart (sanpham_anh, session_idA, sanpham_id, sanpham_tieude, sanpham_gia, color_anh, quantitys, sanpham_size) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->db->link->prepare($query);
    $stmt->bind_param("ssisssss", $sanpham_anh, $session_idA, $sanpham_id, $sanpham_tieude, $sanpham_gia, $color_anh, $quantitys, $sanpham_size);
    $result = $stmt->execute();
    if ($result) {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $cacheKey = "cart:$session_idA";
        $redis->del($cacheKey); // Xóa cache để cập nhật
    }
    return $result;
}

    //  ========= Hiện ra trang giỏ hàng ============
    public function show_cart($session_id) {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379); // Kết nối Redis
    $cacheKey = "cart:$session_id";
    if ($redis->exists($cacheKey)) {
        return json_decode($redis->get($cacheKey), true); // Lấy từ Redis
    }
    $query = "SELECT * FROM tbl_cart WHERE session_idA = ? ORDER BY cart_id DESC";
    $stmt = $this->db->link->prepare($query);
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $redis->setex($cacheKey, 1800, json_encode($data)); // Lưu vào Redis, TTL 30 phút
        return $data;
    }
    return null;
}

    //  ========= Xóa giỏ hàng ===========
    public function delete_cart($cart_id) {
        $query = "DELETE FROM tbl_cart WHERE cart_id = ?";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("i", $cart_id);
        $result = $stmt->execute();
        if ($result) {
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            $session_id = session_id();
            $redis->del("cart:$session_id"); // Xóa cache
            $query = "SELECT * FROM tbl_cart WHERE session_idA = ? ORDER BY cart_id";
            $stmt = $this->db->link->prepare($query);
            $stmt->bind_param("s", $session_id);
            $stmt->execute();
            $resultA = $stmt->get_result();
            if ($resultA->num_rows == 0) {
                Session::set('SL', null);
            }
        }
        return $result;
    }

public function del_data_cart() {
        $session_idA = session_id();
        $query = "DELETE FROM tbl_cart WHERE session_idA = ?";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("s", $session_idA);
        $result = $stmt->execute();
        if ($result) {
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->del("cart:$session_idA"); // Xóa cache
        }
        return $result;
    }

public function show_cartF($session_id){
    $query = "SELECT * FROM tbl_cart WHERE session_idA = '$session_id' ORDER BY cart_id DESC";
    $result = $this -> db ->select($query);
    return $result;
}

public function show_cartB($session_id){
    $query = "SELECT * FROM tbl_cart WHERE session_idA = '$session_id' ORDER BY cart_id DESC";
    $result = $this -> db ->selectdc($query);
    return $result;
}

//  ============== Đăng ký ======================
public function insert_register($data,$file){ 
    $name = $data['name'];
    $address = $data['address'];
    $customer_tinh = $data['customer_tinh'];
    $customer_huyen = $data['customer_huyen'];
    $customer_xa = $data['customer_xa'];
    $phone = $data['phone'];
    $email = $data['email'];
    $password = md5($data['password']);
    $re_password = md5($data['re_password']);
    if($name== "" || $address== "" || $customer_tinh== "" || $customer_huyen== "" || $customer_xa== "" || $phone== "" || $email== "" || $password== "" || $re_password== "" ){
        $alert = "<script>alert('Bạn chưa nhập thông tin ! vui lòng điền vào.')</script>";
        return $alert;
    }else {
        $check_email ="SELECT * FROM tbl_register WHERE email='$email' LIMIT 1";
        $result_check = $this -> db ->select($check_email);
        if($result_check){
            $alert = "<script>alert('Email đã tồn tại ! vui lòng nhập email khác.')</script>";
            return $alert;
        }else{
            $query = "INSERT INTO tbl_register (name,address,customer_tinh,customer_huyen,customer_xa,phone,email,password,re_password ) 
            VALUES ('$name','$address','$customer_tinh','$customer_huyen','$customer_xa','$phone','$email','$password','$re_password')";
        $result = $this ->db ->insert($query);
        if($result){
            $alert="<script>alert('Đăng ký thành công')</script>";
            return $alert;
        }else{
            $alert="<script>alert('Lỗi, Đăng ký thất bại !')</script>";
            return $alert;
        }
        
        }
    }
    
}
//  ======= hiện thông tin các thành phố địa chỉ ===============
public function show_diachi(){
    $query = "SELECT DISTINCT tinh_tp,ma_tinh FROM tbl_diachi ORDER BY ma_tinh";
    $result = $this -> db ->selectdc($query);
    return $result;
}

public function show_diachi_qh($tinh){
    $query = "SELECT DISTINCT tinh_tp,ma_tinh,quan_huyen,ma_qh FROM tbl_diachi WHERE ma_tinh = '$tinh' ORDER BY ma_qh";
    $result = $this -> db ->selectdc($query);
    return $result;
}

public function show_diachi_px($quan_huyen_id){
    $query = "SELECT DISTINCT tinh_tp,ma_tinh,quan_huyen,ma_qh,phuong_xa,ma_px FROM tbl_diachi WHERE ma_qh = '$quan_huyen_id' ORDER BY ma_px";
    $result = $this -> db ->selectdc($query);
    return $result;
}

    // ======= Đăng nhập ============
    public function login_register($data){ 
            $email = $data['email'];
            $password = md5($data['password']);
            if($email== ""|| $password== ""){
                $alert = "<script>alert('Bạn chưa nhập thông tin ! Vui lòng điền vào.')</script>";
                return $alert;
            }else{
                $query = "SELECT * FROM tbl_register WHERE email = '$email' AND password='$password' ";
            $result_checkre = $this ->db ->select($query);
            if($result_checkre!=false){
                $value = $result_checkre->fetch_assoc();
                Session::set('register_login', true);
                Session::set('register_id', $value['id']);
                Session::set('register_name', $value['name']);
                Session::set('register_email', $value['email']);
                header('Location:cart.php');
            }else {
                $alert="<script>alert('Tên đăng nhập hoặc mật khẩu không đúng.')</script>";
                return $alert;
            }

        }
    }

    // ======= hiển thị thông tin đăng nhập =========
    public function show_register($id){
        $query = "SELECT * FROM tbl_register WHERE id = '$id'";
        $result = $this -> db ->select($query);
        return $result;
    }

    //  ======= Sửa thông tin đăng nhập ========
    public function update_register($data,$id){ 
        $name = $data['name'];
        $address = $data['address'];
        $phone = $data['phone'];
        $email = $data['email'];
        if($name== "" || $address== "" || $phone== "" || $email== ""){
            $alert = "<script>alert('Bạn chưa nhập thông tin ! vui lòng điền vào.')</script>";
            return $alert;
        }else {
                $query = "UPDATE tbl_register SET                            
                name = '$name', 
                address = '$address', 
                phone = '$phone', email = '$email' WHERE id = '$id'";
            $result = $this ->db ->insert($query);
            if($result){
                $alert="<script>alert('Cập nhật thành công')</script>";
                return $alert;
            }else{
                $alert="<script>alert('Lỗi, cập nhật thất bại !')</script>";
                return $alert;
            }
            
            
        }
    }

    //  ======= gửi mail ============
    public function register_email($email){
        $query = "SELECT * FROM tbl_register WHERE email = '$email'";
        $result = $this -> db ->select($query);
        if ($result){
            return $result;
        }else{
            echo "<h4 style='color:red;'> Email không tồn tại </h4> <br>";
        }
    }

    public function register_pass($pass, $email){
        
        $query = "UPDATE tbl_register SET password = md5('$pass') , re_password = md5('$pass') WHERE email ='$email'";
        $result = $this -> db ->update($query);
        // $result = mysqli_num_rows($run);
        return $result;
    }

    // Thêm đơn hàng
    public function insert_payment($register_id, $code_oder, $session_idA, $deliver_method, $method_payment, $today) {
        $register_id = (int)$register_id;
        $code_oder = mysqli_real_escape_string($this->db->link, $code_oder);
        $session_idA = mysqli_real_escape_string($this->db->link, $session_idA);
        $deliver_method = mysqli_real_escape_string($this->db->link, $deliver_method);
        $method_payment = mysqli_real_escape_string($this->db->link, $method_payment);
        $today = mysqli_real_escape_string($this->db->link, $today);

        // Kiểm tra giỏ hàng
        $query = "SELECT * FROM tbl_cart WHERE session_idA = '$session_idA' ORDER BY cart_id DESC";
        $resultA = $this->db->select($query);
        if (!$resultA || $resultA->num_rows == 0) {
            return false; // Giỏ hàng rỗng
        }

        // Chuyển dữ liệu từ tbl_cart sang tbl_carta
        $success = true;
        while ($resultB = $resultA->fetch_assoc()) {
            $cart_id = (int)$resultB['cart_id'];
            $sanpham_anh = mysqli_real_escape_string($this->db->link, $resultB['sanpham_anh']);
            $sanpham_id = (int)$resultB['sanpham_id'];
            $sanpham_tieude = mysqli_real_escape_string($this->db->link, $resultB['sanpham_tieude']);
            $sanpham_gia = mysqli_real_escape_string($this->db->link, $resultB['sanpham_gia']);
            $color_anh = mysqli_real_escape_string($this->db->link, $resultB['color_anh']);
            $quantitys = (int)$resultB['quantitys'];
            $sanpham_size = mysqli_real_escape_string($this->db->link, $resultB['sanpham_size']);

            $query = "INSERT INTO tbl_carta (sanpham_anh, session_idA, sanpham_id, sanpham_tieude, sanpham_gia, color_anh, quantitys, sanpham_size) 
                      VALUES ('$sanpham_anh', '$session_idA', '$sanpham_id', '$sanpham_tieude', '$sanpham_gia', '$color_anh', '$quantitys', '$sanpham_size')";
            $resultC = $this->db->insert($query);
            if ($resultC) {
                $query = "DELETE FROM tbl_cart WHERE cart_id = '$cart_id'";
                $resultD = $this->db->delete($query);
                if (!$resultD) {
                    $success = false;
                }
            } else {
                $success = false;
            }
        }

        // Thêm vào tbl_payment
        if ($success) {
            $query = "INSERT INTO tbl_payment (register_id, session_idA, giaohang, thanhtoan, order_date, code_oder) 
                      VALUES ('$register_id', '$session_idA', '$deliver_method', '$method_payment', '$today', '$code_oder')";
            $result = $this->db->insert($query);
            if ($result) {
                // Gửi email
                $title = "Cửa hàng bán quần áo tại Website TEECLUB Bạn đã đặt hàng thành công!";
                $content = "<p style='font-size: 18px;'>Cảm ơn quý khách đã đặt hàng của chúng tôi với mã đơn hàng: <span style='font-size: 20px; color: #378000;'>$code_oder</span></p>";
                $content .= "<h4>Cửa hàng bán quần áo tại Website TEECLUB sẽ lên đơn hàng cho bạn và giao hàng sớm nhất. Thank you for visiting our store.</h4>";
                $addressMail = Session::get('register_email');
                $mail = new Mailer();
                $mail->sendMail($title, $content, $addressMail);

                // Xóa cache Redis và session
                $redis = new Redis();
                $redis->connect('127.0.0.1', 6379);
                $redis->del("cart:$session_idA");
                Session::set('SL', null);
                Session::set('TT', null);

                header('Location: success.php');
                exit();
                return true;
            }
        }
        return false;
    }


    public function show_carta($session_id){
        $query = "SELECT * FROM tbl_carta WHERE session_idA = '$session_id' ORDER BY carta_id DESC LIMIT 1";
        $result = $this -> db ->select($query);
        return $result;
    }

    public function show_payment($session_id) {
        $query = "SELECT * FROM tbl_payment WHERE session_idA = '$session_id' ORDER BY payment_id DESC LIMIT 1";
        $result = $this -> db ->select($query);
        return $result;
    }

    public function show_Cart_detaill($session_id){
        $query = "SELECT * FROM tbl_carta WHERE session_idA = '$session_id' ORDER BY carta_id DESC";
        $result = $this -> db ->select($query);
        return $result;
    }

    // ========  update lại đơn hàng ==================
    public function update_order($status,$session_idA){
        $query = "UPDATE tbl_payment SET statusA = '$status' WHERE session_idA = '$session_idA'";
        $result = $this ->db ->update($query);
        // header('Location:orderlist.php');
        return $result;
    }

    // ======== tìm kiếm sản phẩm ===========
    public function search_product($tukhoa){
        $tukhoa = $this ->fm ->validation($tukhoa);
        $query = "SELECT * FROM tbl_sanpham WHERE sanpham_tieude LIKE '%$tukhoa%'";
        $result = $this -> db ->select($query);
        return $result;
    }

    // ========== bình luận ===============
    public function insert_binhluan($today){
        $sanpham_id = $_POST['sanpham_id_binhluan'];
        $binhluan_ten = $_POST['tenbinhluan'];
        $binhluan = $_POST['binhluan'];
        if($binhluan_ten== "" || $binhluan== ""){
            $alert = "<script>alert('Bạn chưa nhập thông tin bình luận ! vui lòng điền vào.')</script>";
            return $alert;
        }else {
            $query = "INSERT INTO tbl_binhluan (binhluan_ten,binhluan,sanpham_id,binhluan_date) 
            VALUES ('$binhluan_ten','$binhluan','$sanpham_id','$today')";
          $result = $this ->db ->insert($query);
          return $result;
        // if($result){
        //     $alert="<script>alert('Bình luận sẽ được quản trị kiểm duyệt')</script>";
        //     return $alert;
        // }else{
        //     $alert="<script>alert('Lỗi, bình luận thất bại !')</script>";
        //     
        // }
        }
    }

    //  ======== Thông tin tất cả các bình luận ============
    public function show_binhluan(){
        $query = "SELECT tbl_binhluan.*, tbl_sanpham.sanpham_ma
        FROM tbl_binhluan INNER JOIN tbl_sanpham ON tbl_binhluan.sanpham_id = tbl_sanpham.sanpham_id
        ORDER BY tbl_binhluan.binhluan_id DESC  ";
        $result = $this -> db ->select($query);
        return $result;
    }


    // =========== like sản phẩm ==================
    public function insert_wishlist($sanpham_id,$register_id ){
        $sanpham_id = mysqli_real_escape_string($this->db->link, $sanpham_id);
        $register_id = mysqli_real_escape_string($this->db->link, $register_id);
        $query = "SELECT * FROM tbl_wishlist WHERE sanpham_id = '$sanpham_id' AND register_id = '$register_id'";
        $result = $this ->db ->select($query);
        if ($result) {
            $msg = "<script>alert('Sản phẩm đã đc thêm yêu thích.')</script>";
            return $msg ;
        }else {
            $query = "SELECT * FROM tbl_sanpham WHERE sanpham_id = '$sanpham_id'";
            $result = $this ->db ->select($query)-> fetch_assoc();
            
            $sanpham_tieude = $result['sanpham_tieude'];
            $sanpham_gia = $result['sanpham_gia'];
            $sanpham_anh = $result['sanpham_anh'];

            $query = "INSERT INTO tbl_wishlist (sanpham_id,sanpham_gia,sanpham_anh,register_id,sanpham_tieude) 
            VALUES ('$sanpham_id','$sanpham_gia','$sanpham_anh','$register_id','$sanpham_tieude')";
        $result_compare = $this ->db ->insert($query);
        if($result_compare){
            $alert="<script>alert('like thành công')</script>";
            return $alert;
        }else{
            $alert="<script>alert('Lỗi, like thất bại !')</script>";
            return $alert;
        }
        }
    }

    // ==========  hiện ra slider =============
    public function show_slider_all(){
        $query = "SELECT * FROM tbl_slider WHERE type = '1' ORDER BY slider_id DESC";
        $result = $this -> db ->select($query);
        return $result;
    }

    // ======== hiện ra đánh giá sao =========
    public function  get_star($sanpham_id,$register_id){
        $query = "SELECT * FROM tbl_rating WHERE sanpham_id = '$sanpham_id' AND register_id = '$register_id'";
        $result = $this -> db ->select($query);
        return $result;
    }

    // ==========  Insert VNPAY Lưu thông tin thanh toán ===========
    public function insert_vnpay($vnp_Amount,$code_cart,$vnp_BankCode,$vnp_BankTranNo,$vnp_CardType,$vnp_OrderInfo,$vnp_PayDate,$vnp_TmnCode,$vnp_TransactionNo)
    {
           $insert_vnpay = "INSERT INTO tbl_vnpay(vnp_amount,code_cart,vnp_bankcode,vnp_banktranno,vnp_cardtype,vnp_orderinfo,vnp_paydate,vnp_tmncode,vnp_transactionno) 
           VALUE('".$vnp_Amount."','".$code_cart."','".$vnp_BankCode."','".$vnp_BankTranNo."','".$vnp_CardType."','".$vnp_OrderInfo."','".$vnp_PayDate."','".$vnp_TmnCode."','".$vnp_TransactionNo."')";
        $result = $this ->db ->insert($insert_vnpay);
        return $result;  
     
    }

}

?>