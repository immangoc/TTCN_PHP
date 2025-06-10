<?php
include "header.php";
date_default_timezone_set('Asia/Ho_Chi_Minh');
$register_id = Session::get('register_id') ? Session::get('register_id') : null;
$insert_wishlist = null;
$binhluan_insert = null;

if (isset($_GET['sanpham_id']) && !empty($_GET['sanpham_id'])) {
    $sanpham_id = intval($_GET['sanpham_id']);
} else {
    header("Location: index.php");
    exit;
}

if (isset($_POST['binhluan_submit'])) {
    $today = date("d/m/Y");
    $binhluan_insert = $index->insert_binhluan($today);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wishlist'])) {
    if ($register_id) {
        $sanpham_id = intval($_POST['sanpham_id']);
        $insert_wishlist = $index->insert_wishlist($sanpham_id, $register_id);
    } else {
        $insert_wishlist = "<p>Vui lòng đăng nhập để thêm vào danh sách yêu thích.</p>";
    }
}

$get_sanpham = $index->get_sanpham($sanpham_id);
if ($get_sanpham && $get_sanpham->num_rows > 0) {
    $resultE = $get_sanpham->fetch_assoc();
} else {
    echo "<p>Sản phẩm không tồn tại.</p>";
    exit;
}
?>
<section class="product">
    <div class="container">
        <div class="product-top git">
            <p><a href="index.php">Trang chủ</a></p> <span>→</span> 
            <p><?php echo $resultE['danhmuc_ten'] ?></p><span>→</span>
            <p><?php echo $resultE['loaisanpham_ten'] ?></p><span>→</span>
            <p><?php echo $resultE['sanpham_tieude'] ?></p>
        </div>
        <div class="product-content git">
            <?php
            $get_sanpham = $index->get_sanpham($sanpham_id);
            if ($get_sanpham) {
                while ($result = $get_sanpham->fetch_assoc()) {
                    $is_out_of_stock = $result['soluong'] == 0;
            ?>
            <div class="product-content-left git">
                <div class="product-content-left-big-img">
                    <img src="admin/uploads/<?php echo $result['sanpham_anh'] ?>" alt="">
                    <div class="product-content-left-gioithieu">
                        <h3>Thông tin giới thiệu sản phẩm:</h3>
                        <?php echo $result['sanpham_gioithieu'] ?>
                    </div>
                </div>
                <div class="product-content-left-small-img">
                    <?php
                    $sanpham_id = $result['sanpham_id'];
                    $get_anh = $index->get_anh($sanpham_id);
                    if ($get_anh) {
                        while ($resultA = $get_anh->fetch_assoc()) {
                    ?>
                    <img src="admin/uploads/<?php echo $resultA['sanpham_anh'] ?>" alt="">
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="product-content-right">
                <div class="product-content-right-product-name">
                    <input class="session_id" type="hidden" value="<?php echo session_id() ?>">
                    <input class="sanpham_id" type="hidden" value="<?php echo $result['sanpham_id'] ?>">
                    <h1 class="sanpham_tieude"><?php echo $result['sanpham_tieude'] ?></h1> 
                    <div class="product-content-right-p">SKU: <span class="sanpham_ma"><?php echo $result['sanpham_ma'] ?></span> 
                    <ul class="product-content-right-rating">
                        <?php
                        if (Session::get('register_id')) {
                            $register_id = Session::get('register_id');
                            $get_star = $index->get_star($sanpham_id, $register_id);
                            if ($get_star) {
                                $tongsao = 0;
                                $trungbinhsao = 0;
                                $solan = 0;
                                while ($result_star = $get_star->fetch_assoc()) {
                                    $tongsao += $result_star['rating'];
                                    $solan += 1;
                                    $trungbinhsao = $tongsao / $solan;
                                }
                            }
                            if ($get_star) {
                                for ($count = 1; $count <= 5; $count++) {
                                    $color = ($count <= round($trungbinhsao)) ? 'color:#ffcc00;' : 'color:#ccc;';
                        ?>
                        <li class="rating" style="cursor: pointer; font-size: 25px;<?php echo $color ?>"
                            id="<?php echo $result['sanpham_id'] ?>-<?php echo $count ?>"
                            data-sanpham_id="<?php echo $result['sanpham_id'] ?>"
                            data-rating="<?php echo $count ?>"
                            data-index="<?php echo $count ?>"
                            data-register_id="<?php echo Session::get('register_id') ?>">★</li>
                        <?php
                                }
                        ?>
                        <span class="product-content-right-p-span">(<?php echo round($trungbinhsao) ?>/5 đánh giá)</span>
                        <?php
                            } else {
                                for ($count = 1; $count <= 5; $count++) {
                                    $color = 'color:#ccc;';
                        ?>
                        <li class="rating" style="cursor: pointer; font-size: 25px;<?php echo $color ?>"
                            id="<?php echo $result['sanpham_id'] ?>-<?php echo $count ?>"
                            data-sanpham_id="<?php echo $result['sanpham_id'] ?>"
                            data-rating="<?php echo $count ?>"
                            data-index="<?php echo $count ?>"
                            data-register_id="<?php echo Session::get('register_id') ?>">★</li>
                        <?php
                                }
                        ?>
                        <span class="product-content-right-p-span">(0/5 đánh giá)</span>
                        <?php
                            }
                        } else {
                            for ($count = 1; $count <= 5; $count++) {
                        ?>
                        <li class="rating_login" style="cursor: pointer; font-size: 25px; color:#ffcc00;">★</li>
                        <?php
                            }
                        ?>
                        <span class="product-content-right-p-span">(0/5 đánh giá)</span>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="product-content-right-product-price">
                    <?php $TGG = $result['sanpham_gia'] * ((100 - $result['giamgia']) / 100) ?>
                    <span><?php $resultC = number_format($TGG); echo $resultC ?></span><sup>đ</sup>
                    <input class="sanpham_gia" type="hidden" value="<?php echo $TGG ?>">
                    <?php if ($result['giamgia'] > 0) { ?>
                    <div class="product-detail__price-sale"><?php echo $result['giamgia'] ?><span>%</span></div>
                    <?php } ?>
                </div>
                <div class="product-content-right-product-color">
                    <p><span style="font-weight: bold;">Màu sắc: </span><?php echo $result['color_ten'] ?> <span style="color: red;">*</span></p>
                    <div class="product-content-right-product-color-IMG">
                        <img class="anh_color" src="admin/uploads/<?php echo $result['color_anh'] ?>" alt="">
                        <img class="sanpham_anh" style="display: none;" src="admin/uploads/<?php echo $result['sanpham_anh'] ?>" alt="">
                    </div>
                </div>
                <div class="product-content-right-product-size">
                    <p style="font-weight: bold">Size: </p>
                    <div class="size">
                        <?php
                        $sanpham_id = $result['sanpham_id'];
                        $get_size = $index->get_size($sanpham_id);
                        if ($get_size) {
                            while ($resultV = $get_size->fetch_assoc()) {
                        ?>
                        <div class="size-item">
                            <input class="size-item-input" value="<?php echo $resultV['sanpham_size'] ?>" name="size-item" type="radio">
                            <span><?php echo $resultV['sanpham_size'] ?></span>
                        </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="quantity">
                        <p style="font-weight: bold">Số lượng: </p>
                        <input class="soluong_sp" type="hidden" value="<?php echo $result['soluong'] ?>">
                        <input class="quantitys" type="number" min="1" max="<?php echo $result['soluong'] ?>" value="1" <?php echo $is_out_of_stock ? 'disabled' : '' ?>>
                        <p style="font-weight: bold; margin-left: 10px;">Tồn kho: <?php echo $result['soluong'] ?></p>
                    </div>
                    <?php if ($is_out_of_stock) { ?>
                        <p class="alert_soluong" style="color: red;">Sản phẩm đã hết hàng!</p>
                    <?php } ?>
                    <p class="size-alert" style="color: red;"></p>
                </div>
                <div class="product-content-right-product-button">
                    <button class="add-cart-btn" data-action="add-to-cart" <?php echo $is_out_of_stock ? 'disabled' : '' ?>>
                        <i class="fas fa-shopping-cart"></i> <p>THÊM GIỎ HÀNG</p>
                    </button>
                    <button class="buy-now-btn" data-action="buy-now" <?php echo $is_out_of_stock ? 'disabled' : '' ?>>
                        <p>MUA HÀNG</p>
                    </button>
                    <form action="" method="POST">
                        <input type="hidden" value="<?php echo $sanpham_id ?>" name="sanpham_id">
                        <?php 
                        $id = Session::get('register_login');
                        if ($id) {
                            echo '<button class="like_button" name="wishlist"><i class="like-i-button fa-regular fa-heart"></i></button>';
                        }
                        ?>
                        <?php if (isset($insert_wishlist)) echo $insert_wishlist; ?>
                    </form>
                </div>
                <div class="product-content-right-bottom">
                    <div class="product-content-right-bottom-top">∨</div>
                    <div class="product-content-right-bottom-content-big">
                        <div class="product-content-right-bottom-title">
                            <div class="product-content-right-bottom-title-item chitiet">
                                <p>Chi tiết</p>
                            </div>
                            <div class="product-content-right-bottom-title-item baoquan">
                                <p>Bảo quản</p>
                            </div>
                        </div>
                        <div class="product-content-right-bottom-content">
                            <div class="product-content-right-bottom-content-chitiet">
                                <?php echo $result['sanpham_chitiet'] ?>
                            </div>
                            <div class="product-content-right-bottom-content-baoquan">
                                <?php echo $result['sanpham_baoquan'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</section>
<section class="product-gallery-one">
    <div class="row">
        <div class="product-gallery-one-content">
            <div class="product-gallery-one-content-title">
                <h1 class="product-gallery-ones-h1">NEW ARRIVAL</h1>
                <ul>
                    <li class="tab-women">GIRL</li>
                    <li class="tab-men">MEN</li>
                    <li class="tab-kid">KIDS</li>
                </ul>
            </div>
            <div class="home-product">
                <div class="grid__row">
                    <?php
                    $show_product = $index->show_product();
                    if ($show_product) {
                        while ($resultB = $show_product->fetch_assoc()) {
                            if ($resultB['sanpham_host'] == 1 && $resultB['tinhtrang'] == '1') {
                    ?>
                    <div class="grid__colum-2-4">
                        <div class="home-product-item">
                            <a href="product.php?sanpham_id=<?php echo $resultB['sanpham_id'] ?>">
                                <div class="home-product-item_img" style="background-image: url(admin/uploads/<?php echo $resultB['sanpham_anh'] ?>);"></div>
                            </a>
                            <a href="product.php?sanpham_id=<?php echo $resultB['sanpham_id'] ?>">
                                <h4 class="home-product-item_name"><?php echo $resultB['sanpham_tieude'] ?></h4>
                            </a>
                            <div class="home-product-item_price">
                                <p style="font-size: 1.4rem; margin: 0 8px;">Giá:</p>
                                <?php 
                                if ($resultB['giamgia'] > 0) { 
                                    $TGG = $resultB['sanpham_gia'] * ((100 - $resultB['giamgia']) / 100);
                                ?>
                                <span class="home-product-item_price-old"><?php echo number_format($resultB['sanpham_gia']) ?><sup>đ</sup></span>
                                <span class="home-product-item_price-current"><?php echo number_format($TGG) ?><sup>đ</sup></span>
                                <?php } else { ?>
                                <span class="home-product-item_price-current"><?php echo number_format($resultB['sanpham_gia']) ?><sup>đ</sup></span>
                                <?php } ?>
                            </div>
                            <div class="home-product-item_action">
                                <span class="home-product-item_like home-product-item_like-liked">
                                    <i class="home-product-item_like-icon fa-regular fa-heart"></i>
                                    <i class="home-product-item_like-icon-fill fa-solid fa-heart"></i>
                                </span>
                                <img class="home-product-item_img-alt" src="assets/img/hoatoc1.jpg" alt="">
                            </div>
                            <div class="home-product-item_origin">
                                <span class="home-product-item_brand">TeeClub</span>
                                <span class="home-product-item_origin-name">Hà Nội</span>
                            </div>
                            <div class="home-product-item_favourite">
                                <i class="fa-solid fa-check"></i><span>Yêu thích</span>
                            </div>
                            <?php if ($resultB['giamgia'] > 0) { ?>
                            <div class="home-product-item_sale-off">
                                <span class="home-product-item_sale-off-label">GIẢM</span>
                                <span class="home-product-item_sale-off-percent"><?php echo $resultB['giamgia'] ?>%</span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                            }
                        }
                    }
                    ?>
                </div>
                <div class="link-product"></div>
            </div>
            <div class="home-product-pro">
                <div class="grid__row">
                    <?php
                    $show_product = $index->show_product();
                    if ($show_product) {
                        while ($resultB = $show_product->fetch_assoc()) {
                            if ($resultB['sanpham_host'] == 2 && $resultB['tinhtrang'] == '1') {
                    ?>
                    <div class="grid__colum-2-4">
                        <div class="home-product-item">
                            <a href="product.php?sanpham_id=<?php echo $resultB['sanpham_id'] ?>">
                                <div class="home-product-item_img" style="background-image: url(admin/uploads/<?php echo $resultB['sanpham_anh'] ?>);"></div>
                            </a>
                            <a href="product.php?sanpham_id=<?php echo $resultB['sanpham_id'] ?>">
                                <h4 class="home-product-item_name"><?php echo $resultB['sanpham_tieude'] ?></h4>
                            </a>
                            <div class="home-product-item_price">
                                <p style="font-size: 1.4rem; margin: 0 8px;">Giá:</p>
                                <?php 
                                if ($resultB['giamgia'] > 0) { 
                                    $TGG = $resultB['sanpham_gia'] * ((100 - $resultB['giamgia']) / 100);
                                ?>
                                <span class="home-product-item_price-old"><?php echo number_format($resultB['sanpham_gia']) ?><sup>đ</sup></span>
                                <span class="home-product-item_price-current"><?php echo number_format($TGG) ?><sup>đ</sup></span>
                                <?php } else { ?>
                                <span class="home-product-item_price-current"><?php echo number_format($resultB['sanpham_gia']) ?><sup>đ</sup></span>
                                <?php } ?>
                            </div>
                            <div class="home-product-item_action">
                                <span class="home-product-item_like home-product-item_like-liked">
                                    <i class="home-product-item_like-icon fa-regular fa-heart"></i>
                                    <i class="home-product-item_like-icon-fill fa-solid fa-heart"></i>
                                </span>
                                <img class="home-product-item_img-alt" src="assets/img/hoatoc1.jpg" alt="">
                            </div>
                            <div class="home-product-item_origin">
                                <span class="home-product-item_brand">TeeClub</span>
                                <span class="home-product-item_origin-name">Hà Nội</span>
                            </div>
                            <div class="home-product-item_favourite">
                                <i class="fa-solid fa-check"></i><span>Yêu thích</span>
                            </div>
                            <?php if ($resultB['giamgia'] > 0) { ?>
                            <div class="home-product-item_sale-off">
                                <span class="home-product-item_sale-off-label">GIẢM</span>
                                <span class="home-product-item_sale-off-percent"><?php echo $resultB['giamgia'] ?>%</span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                            }
                        }
                    }
                    ?>
                </div>
                <div class="link-product"></div>
            </div>
            <div class="home-product-produ">
                <div class="grid__row">
                    <?php
                    $show_product = $index->show_product();
                    if ($show_product) {
                        while ($resultB = $show_product->fetch_assoc()) {
                            if ($resultB['sanpham_host'] == 3 && $resultB['tinhtrang'] == '1') {
                    ?>
                    <div class="grid__colum-2-4">
                        <div class="home-product-item">
                            <a href="product.php?sanpham_id=<?php echo $resultB['sanpham_id'] ?>">
                                <div class="home-product-item_img" style="background-image: url(admin/uploads/<?php echo $resultB['sanpham_anh'] ?>);"></div>
                            </a>
                            <a href="product.php?sanpham_id=<?php echo $resultB['sanpham_id'] ?>">
                                <h4 class="home-product-item_name"><?php echo $resultB['sanpham_tieude'] ?></h4>
                            </a>
                            <div class="home-product-item_price">
                                <p style="font-size: 1.4rem; margin: 0 8px;">Giá:</p>
                                <?php 
                                if ($resultB['giamgia'] > 0) { 
                                    $TGG = $resultB['sanpham_gia'] * ((100 - $resultB['giamgia']) / 100);
                                ?>
                                <span class="home-product-item_price-old"><?php echo number_format($resultB['sanpham_gia']) ?><sup>đ</sup></span>
                                <span class="home-product-item_price-current"><?php echo number_format($TGG) ?><sup>đ</sup></span>
                                <?php } else { ?>
                                <span class="home-product-item_price-current"><?php echo number_format($resultB['sanpham_gia']) ?><sup>đ</sup></span>
                                <?php } ?>
                            </div>
                            <div class="home-product-item_action">
                                <span class="home-product-item_like home-product-item_like-liked">
                                    <i class="home-product-item_like-icon fa-regular fa-heart"></i>
                                    <i class="home-product-item_like-icon-fill fa-solid fa-heart"></i>
                                </span>
                                <img class="home-product-item_img-alt" src="assets/img/hoatoc1.jpg" alt="">
                            </div>
                            <div class="home-product-item_origin">
                                <span class="home-product-item_brand">TeeClub</span>
                                <span class="home-product-item_origin-name">Hà Nội</span>
                            </div>
                            <div class="home-product-item_favourite">
                                <i class="fa-solid fa-check"></i><span>Yêu thích</span>
                            </div>
                            <?php if ($resultB['giamgia'] > 0) { ?>
                            <div class="home-product-item_sale-off">
                                <span class="home-product-item_sale-off-label">GIẢM</span>
                                <span class="home-product-item_sale-off-percent"><?php echo $resultB['giamgia'] ?>%</span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                            }
                        }
                    }
                    ?>
                </div>
                <div class="link-product"></div>
            </div>
        </div>
    </div>
</section>
<?php 
if (isset($binhluan_insert)) {
    echo $binhluan_insert;
}
?>
<div class="row">
    <div class="container-list">
        <h4 class="comment-list-h4">Ý kiến bình luận sản phẩm</h4>
        <form action="#" method="POST">
            <p><input type="hidden" value="<?php echo $sanpham_id ?>" name="sanpham_id_binhluan"></p>
            <p><input class="comment-list-input" type="text" placeholder="Điền tên" required name="tenbinhluan"></p>
            <p><textarea class="comment-list-tetarea" name="binhluan" id="" cols="30" rows="10" placeholder="Mời bạn bình luận hoặc đặt câu hỏi"></textarea></p>
            <button name="binhluan_submit" class="comment-list-button">Gửi bình luận</button>
        </form>
        <?php
        $show_binhluan = $index->show_binhluan();
        if ($show_binhluan) {
            $i = 0;
            while ($result = $show_binhluan->fetch_assoc()) {
                $i++;
        ?>
        <ul class="comment-list">
            <div class="cmt-top">
                <p class="cmt-top-name"><?php echo $result['binhluan_ten'] ?></p>
            </div>
            <div class="cmt-content">
                <p class="cmt-txt"><?php echo $result['binhluan'] ?></p>
            </div>
            <div class="cmt-command">
                <p class="cmtl dot-circle-ava"><i class="fa-regular fa-thumbs-up"></i> thích</p>
                <span class="cmtd dot-circle-ava"><?php echo $result['binhluan_date'] ?></span>
            </div>
            <li class="cmt-command-li"><p class="cmttl">Trả lời</p>
                <ul>
                    <form action="#" method="POST">
                        <p><input type="hidden" value="<?php echo $sanpham_id ?>" name="sanpham_id_binhluan"></p>
                        <p><input class="form-binhluan-input" type="text" placeholder="Điền tên" name="tenbinhluan"></p>
                        <p><textarea class="form-binhluan-tetarea" name="binhluan" id="" cols="30" rows="10" placeholder="Mời bạn bình luận hoặc đặt câu hỏi"></textarea></p>
                        <button name="binhluan_submit" class="form-binhluan-button">Gửi bình luận</button>
                    </form>
                </ul>
            </li>
        </ul>
        <?php
            }
        }
        ?>
    </div>
</div>
<script>
$(document).ready(function() {
    var selectedSize = '';

    $('.size-item-input').on('change', function() {
        selectedSize = $(this).val();
        $('.size-alert').text(''); 
    });

    function addToCart(button, redirect = false) {
        var $parent = button.closest('.product-content-right');
        var session_id = $parent.find('.session_id').val();
        var sanpham_id = $parent.find('.sanpham_id').val();
        var sanpham_tieude = $parent.find('.sanpham_tieude').text();
        var sanpham_anh = $parent.find('.sanpham_anh').attr('src');
        var sanpham_gia = $parent.find('.sanpham_gia').val();
        var color_anh = $parent.find('.anh_color').attr('src');
        var quantity = $parent.find('.quantitys').val();
        var soluong_sp = $parent.find('.soluong_sp').val();

        // Kiểm tra nếu hết hàng
        if (parseInt(soluong_sp) === 0) {
            $('.alert_soluong').text('Sản phẩm đã hết hàng!');
            return;
        }

        // Kiểm tra chọn size
        if (!selectedSize) {
            $('.size-alert').text('Vui lòng chọn size*');
            return;
        }

        // Kiểm tra số lượng
        if (parseInt(quantity) <= 0) {
            $('.alert_soluong').text('Số lượng phải lớn hơn 0*');
            return;
        }

        if (parseInt(quantity) > parseInt(soluong_sp)) {
            $('.alert_soluong').text('Số lượng đặt hàng phải nhỏ hơn hoặc bằng số lượng tồn kho*');
            return;
        }

        $.ajax({
            url: "ajax/cart_ajax.php",
            method: "POST",
            data: {
                session_id: session_id,
                sanpham_id: sanpham_id,
                sanpham_tieude: sanpham_tieude,
                sanpham_anh: sanpham_anh,
                sanpham_gia: sanpham_gia,
                color_anh: color_anh,
                quantitys: quantity,
                sanpham_size: selectedSize
            },
            success: function(data) {
                $('.alert_soluong').text('Thêm vào giỏ hàng thành công!');
                $('.size-alert').text('');
                if (redirect) {
                    window.location.href = 'cart.php';
                }
            },
            error: function() {
                $('.alert_soluong').text('Có lỗi xảy ra, vui lòng thử lại!');
            }
        });
    }

    $('.add-cart-btn').on('click', function() {
        addToCart($(this));
    });

    $('.buy-now-btn').on('click', function() {
        addToCart($(this), true);
    });
});

const itembinhluan = document.querySelectorAll(".cmt-command-li");
itembinhluan.forEach(function(menu, index) {
    menu.addEventListener("click", function() {
        menu.classList.toggle("block");
    });
});
</script>
<?php 
include "footer.php";
?>