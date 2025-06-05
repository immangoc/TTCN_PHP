<?php 
    include "header.php";

    function convertToWebP($source, $quality = 80) {
        if (!file_exists($source)) return $source; // Không tồn tại ảnh gốc
    
        $info = getimagesize($source);
        if (!$info || !isset($info['mime'])) return $source;
    
        $ext = pathinfo($source, PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
            return $source; // Chỉ xử lý jpg, jpeg, png
        }
    
        $destination = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $source);
        if (file_exists($destination)) return $destination;
    
        try {
            if ($info['mime'] == 'image/jpeg') {
                $image = imagecreatefromjpeg($source);
            } elseif ($info['mime'] == 'image/png') {
                $image = imagecreatefrompng($source);
                // Xóa nền trong suốt (do webp không hỗ trợ alpha transparency tốt trong PHP)
                $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
                $white = imagecolorallocate($bg, 255, 255, 255);
                imagefill($bg, 0, 0, $white);
                imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                imagedestroy($image);
                $image = $bg;
            } else {
                return $source;
            }
    
            imagewebp($image, $destination, $quality);
            imagedestroy($image);
            return $destination;
        } catch (Exception $e) {
            return $source;
        }
    }
?>
<div class="row">
    <section class="Slider">
        <div class="aspect-ratio-169">
            <?php
                $show_slider_all = $index->show_slider_all();
                if ($show_slider_all) {
                    while ($result = $show_slider_all->fetch_assoc()) {
                        $original = "admin/uploads/" . $result['slider_image'];
                        $webpPath = convertToWebP($original);
            ?>
            <a href="">
                <img src="<?php echo $webpPath ?>" alt="" loading="lazy">
            </a>
            <?php }} ?>
        </div>
        <div class="dot-container">
            <div class="dot active"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </section>

    <section class="band">
        <div class="band-content-imgs">
            <?php
                $bannerImg = "assets/img/bar1.jpg";
                $bannerWebp = convertToWebP($bannerImg);
            ?>
            <a href=""><img src="<?php echo $bannerWebp ?>" alt="" loading="lazy"></a>
        </div>
    </section>

    <section class="home-promote band">
        <div class="title-section">SALE HÈ GIẢI NHIỆT</div>
        <div class="list-promote d-flex">
            <?php 
                $promos = ["qwd1 (1).jpg", "qwd3 (1).jpg", "qwd4 (1).jpg", "qwd2 (1).jpg"];
                $titles = [
                    "Set đi chơi - style trẻ trung",
                    "Set đi tiệc - style sang trọng",
                    "Set đi làm - style thanh lịch",
                    "Set xuống phố - style hiện đại"
                ];
                foreach ($promos as $idx => $img) {
                    $src = "assets/img/" . $img;
                    $webp = convertToWebP($src);
            ?>
            <div class="item-promote">
                <a href="#"><img src="<?php echo $webp ?>" alt="Set đồ" class="promote-image lazy" loading="lazy"></a>
                <div class="title-promote"><a href="#"><?php echo $titles[$idx] ?></a></div>
            </div>
            <?php } ?>
        </div>
    </section>

    <section class="product-gallery-one">
        <div class="row">
            <div class="product-gallery-one-content">
                <div class="product-gallery-one-content-title">
                    <h1 class="product-gallery-ones-h1"> NEW ARRIVAL</h1>
                    <ul>
                        <li class="tab-women"> GIRL</li>
                        <li class="tab-men"> MEN</li>
                        <li class="tab-kid"> KIDS</li>
                    </ul>
                </div>

                <?php
                    function renderProducts($index, $hostId) {
                        $show_product = $index->show_product();
                        if ($show_product) {
                            while ($resultB = $show_product->fetch_assoc()) {
                                if ($resultB['sanpham_host'] == $hostId && $resultB['tinhtrang'] == '1') {
                                    $original = "admin/uploads/" . $resultB['sanpham_anh'];
                                    $webp = convertToWebP($original);
                                    $price = number_format($resultB['sanpham_gia']);
                                    $discount = $resultB['giamgia'];
                                    $discountPrice = number_format($resultB['sanpham_gia'] * ((100 - $discount) / 100));
                ?>
                <div class="grid__colum-2-4">
                    <div class="home-product-item">
                        <a href="product.php?sanpham_id=<?php echo $resultB['sanpham_id'] ?>">
                            <div class="home-product-item_img" style="background-image: url(<?php echo $webp ?>);"></div>
                        </a>
                        <a href="product.php?sanpham_id=<?php echo $resultB['sanpham_id'] ?>">
                            <h4 class="home-product-item_name"><?php echo $resultB['sanpham_tieude'] ?></h4>
                        </a>
                        <div class="home-product-item_price">
                            <p style="font-size: 1.4rem; margin: 0 8px;">Giá:</p>
                            <?php if ($discount > 0) { ?>
                                <span class="home-product-item_price-old"><?php echo $price ?><sup>đ</sup></span>
                                <span class="home-product-item_price-current"><?php echo $discountPrice ?><sup>đ</sup></span>
                            <?php } else { ?>
                                <span class="home-product-item_price-current"><?php echo $price ?><sup>đ</sup></span>
                            <?php } ?>
                        </div>
                        <div class="home-product-item_action">
                            <span class="home-product-item_like home-product-item_like-liked">
                                <i class="home-product-item_like-icon fa-regular fa-heart"></i>
                                <i class="home-product-item_like-icon-fill fa-solid fa-heart"></i>
                            </span>
                            <?php
                                $altImg = convertToWebP("assets/img/hoatoc1.jpg");
                            ?>
                            <img class="home-product-item_img-alt" src="<?php echo $altImg ?>" alt="" loading="lazy">
                        </div>
                        <div class="home-product-item_origin">
                            <span class="home-product-item_brand">TEECLUB</span>
                            <span class="home-product-item_origin-name">Hà Nội</span>
                        </div>
                        <div class="home-product-item_favourite">
                            <i class="fa-solid fa-check"></i><span> Yêu thích</span>
                        </div>
                        <?php if ($discount > 0) { ?>
                            <div class="home-product-item_sale-off">
                                <span class="home-product-item_sale-off-label">GIẢM</span>
                                <span class="home-product-item_sale-off-percent"><?php echo $discount ?>%</span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php
                                }
                            }
                        }
                    }
                ?>

                <div class="home-product">
                    <div class="grid__row">
                        <?php renderProducts($index, 1); ?>
                    </div>
                </div>

                <div class="home-product-pro">
                    <div class="grid__row">
                        <?php renderProducts($index, 2); ?>
                    </div>
                </div>

                <div class="home-product-produ">
                    <div class="grid__row">
                        <?php renderProducts($index, 3); ?>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<?php 
    include "footer.php";
?>
