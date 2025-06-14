<?php 
  include "header.php";
?>
<?php
if(!isset($_GET['id'])){
    echo "<meta http-equiv='refresh' content='0; url=?id=live'>";
}
?>

    <!-- -----------------------CART---------------------------------------------- -->
<section class="cart">
    <div class="row">
        <div class="cart-top-wrap">
            <div class="cart-top">
                <div class="cart-top-cart cart-top-item">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="cart-top-adress cart-top-item">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="cart-top-payment cart-top-item">
                    <i class="fas fa-money-check-alt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php 
        $session_id = session_id();
        $show_cart = $index->show_cart($session_id); // Gọi show_cart một lần
        if ($show_cart) {
        ?>
            <div class="cart-content git git_block">
                <div class="cart-content-left">
                    <table>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Màu</th>
                            <th>Size</th>
                            <th>SL</th>
                            <th>Giá</th>
                            <th>Xóa</th>
                        </tr>
                        <?php
                        $SL = 0;
                        $TT = 0;
                        foreach ($show_cart as $result) {
                        ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($result['sanpham_anh']); ?>" alt=""></td>
                                <td><p><?php echo htmlspecialchars($result['sanpham_tieude']); ?></p></td>
                                <td><img src="<?php echo htmlspecialchars($result['color_anh']); ?>" alt=""></td>
                                <td><p><?php echo htmlspecialchars($result['sanpham_size']); ?></p></td>
                                <td><span><?php echo (int)$result['quantitys']; ?></span></td>
                                <td><p><?php echo number_format((int)$result['sanpham_gia']); ?><sup>đ</sup></p></td>
                                <td><a href="cartdelete.php?cart_id=<?php echo (int)$result['cart_id']; ?>"><i class="trash_i-cart fas fa-trash-alt"></i></a></td>
                                <?php 
                                $a = (int)$result['sanpham_gia']; 
                                $b = (int)$result['quantitys']; 
                                $TTA = $a * $b; 
                                $SL += $b;
                                $TT += $TTA;
                                ?>
                            </tr>
                        <?php
                        }
                        Session::set('SL', $SL);
                        Session::set('TT', number_format($TT));
                        ?>
                    </table>
                </div>
                <div class="cart-content-right">
                    <table>
                        <tr>
                            <th colspan="2"><p>TỔNG TIỀN GIỎ HÀNG</p></th>
                        </tr>
                        <tr>
                            <td>TỔNG SẢN PHẨM</td>
                            <td><?php echo number_format($SL); ?></td>
                        </tr>
                        <tr>
                            <td>TỔNG TIỀN HÀNG</td>
                            <td><p><?php echo number_format($TT); ?><sup>đ</sup></p></td>
                        </tr>
                        <tr>
                            <td>THÀNH TIỀN</td>
                            <td><p><?php echo number_format($TT); ?><sup>đ</sup></p></td>
                        </tr>
                        <tr>
                            <td>TẠM TÍNH</td>
                            <td><p style="font-weight: bold; color: black;"><?php echo number_format($TT); ?><sup>đ</sup></p></td>
                        </tr>
                    </table>
                    <div class="cart-content-right-text">
                        <p>Bạn sẽ được miễn phí ship khi đơn hàng của bạn có tổng giá trị trên 2,000,000<sup>đ</sup></p><br>
                        <?php if ($TT >= 2000000) { ?>
                            <p style="color: red; font-weight: bold;">Đơn hàng của bạn đủ điều kiện được <span style="font-size: 18px;">Free</span> ship</p>
                        <?php } else { ?>
                            <p style="color: red; font-weight: bold;">Mua thêm <span style="font-size: 18px;"><?php echo number_format(2000000 - $TT); ?><sup>đ</sup></span> để được miễn phí SHIP</p>
                        <?php } ?>
                    </div>
                    <div class="cart-content-right-button">
                        <a href="index.php"><button>TIẾP TỤC MUA SẮM</button></a>
                        <?php
                        $login_check = Session::get('register_login');
                        if ($login_check == false) {
                            echo "<span class='cart_not-content'>Bạn chưa đăng nhập thông tin ! Vui lòng đăng nhập để Thanh Toán.</span>";
                        } else {
                            echo '<a href="pay.php"><button>THANH TOÁN</button></a>';
                        }
                        ?>
                    </div>
                    <div class="cart-content-right-dangnhap">
                        <p>TÀI KHOẢN TEECLUB</p><br>
                        <p>Hãy <a href="" style="font-weight: bold; font-size: 1.5rem;">Đăng nhập</a> tài khoản của bạn để tích điểm thành viên.</p>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <p class="p-cart-hang git">Bạn vẫn chưa thêm sản phẩm nào vào giỏ hàng, Vui lòng chọn sản phẩm nhé!</p>
        <?php } ?>
    </div>
</section>

    <?php 
    include "footer.php";
    ?>