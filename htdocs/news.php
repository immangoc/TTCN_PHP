<?php 
  include "header.php";
?>


    <div class="app_container">
        <div class="cartegory-top row">
            <a href="index.php">
                <p>Trang chủ</p>
            </a><span>&#10230;</span>
            <p>Tin Tức</p> <span>&#10230;</span>
            <p>ƯU ĐÃI THÁNG 5: MUA 1 TẶNG 1 - NHẬN NGAY QUÀ TẶNG 0Đ</p>
        </div>
        
        
        <div class="row">
            <div class="grid__row app_content">
                <div class="grid__colum-2 new-grid_item">
                    <nav class="category">
                        <h3 class="category_heading">
                            <i class="category_heading-icon fa-solid fa-list"></i> Danh mục
                        </h3>
                        <ul class="category_list">
                            <li class="category_item category_item--active">
                                <a href="" class="category_item_link">Sự kiện thời trang </a>
                            </li>
                            <li class="category_item ">
                                <a href="" class="category_item_link">Hoặt động cộng đồng</a>
                            </li>
                            <li class="category_item ">
                                <a href="" class="category_item_link">Tin nội bộ</a>
                            </li>
                            <li class="category_item ">
                                <a href="" class="category_item_link">Blog chia sẻ</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="grid_new-10">
                    <div class="grid_new-6">
                        <div class="content-news">
                            <div class="news__title">
                                <h2>
                                    ƯU ĐÃI THÁNG 5: MUA 1 TẶNG 1 - NHẬN NGAY QUÀ TẶNG 0Đ
                                </h2>
                                <div class="time">19/05/2025</div>
                            </div>
                            <div class="news__content">
                                <p>Chào đón tháng 5 rực rỡ cùng IVY Moda với chương trình ưu đãi đặc biệt: <strong>MUA 1 TẶNG 1</strong> – cơ hội sở hữu ngay những phần quà 0Đ vô cùng hấp dẫn. Đừng bỏ lỡ!</p>
                                <p><img src="assets/img/sli2.jpg" alt="Ưu đãi tháng 5 tại IVY Moda"></p>
                                <h3>Thông tin chương trình:</h3>
                                <p>Chỉ cần mua 1 sản phẩm nguyên giá, bạn sẽ được <strong>tặng ngay 1 sản phẩm bất kỳ</strong> nằm trong danh sách ưu đãi dưới đây:</p>
                                    <ul>
                                        <li>Sản phẩm chính: <a href="#">Xem danh sách sản phẩm nguyên giá</a></li>
                                        <li>Sản phẩm tặng kèm 0Đ: <a href="#">Xem danh sách quà tặng</a></li>
                                    </ul>
                                <h3>Hướng dẫn mua hàng:</h3>
                                    <ol>
                                        <li>Chọn sản phẩm nguyên giá mà bạn yêu thích (không áp dụng cho sản phẩm đang khuyến mại).</li>
                                        <li>Thêm sản phẩm quà tặng vào giỏ hàng. Lưu ý: Giá gốc của sản phẩm tặng phải nhỏ hơn hoặc bằng giá của sản phẩm chính.</li>
                                        <li>Tiến hành thanh toán và nhận quà tặng 0Đ cùng đơn hàng.</li>
                                    </ol>
                                <h3>Một số lưu ý quan trọng:</h3>
                                    <ul>
                                        <li>Chương trình <strong>chỉ áp dụng cho sản phẩm nguyên giá</strong>, không áp dụng đồng thời với các chương trình khuyến mại khác.</li>
                                        <li>Sản phẩm tặng kèm phải có <strong>giá trị bằng hoặc thấp hơn</strong> sản phẩm chính.</li>
                                        <li><strong>Mua càng nhiều, nhận quà càng lớn</strong> – số lượng quà tặng tương ứng với số lượng sản phẩm chính được mua.</li>
                                    </ul>
                                    <p>Khám phá ngay thêm nhiều ưu đãi khác tại <a href="index.php">Website chính thức của TEECLUB</a> và rinh về những món quà bất ngờ dành riêng cho bạn!</p>
                                </div>

                        </div>
                    </div>
                    <div class="grid_new-4">
                        <section class="bg-before">
                            <a href="" alt="News Sale">
                                <img src="assets/img/sale1.png" alt="News Sale">
                            </a>
                        </section>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <section class="product-new">
        <div class="product-gallery-one row">
            <div class="row">
                <div class="product-gallery-one-content">
                    <div class="product-gallery-one-content-title product-gallery-two-block">
                        <h1 class="product-gallery-ones-h1"> NEW ARRIVAL - MUA 1 ĐƯỢC 2</h1>
                        <ul class="product_gallery-name">
                            <li class="tab-women"> GIRL</li>
                            <li class="tab-men"> MEN</li>
                            <li class="tab-kid"> KIDS</li>
                        </ul>
                    </div>
                    <div class="home-product ">
                        <div class="grid__row">
                            <?php
                            $show_product = $index ->show_product();
                            if($show_product){ while($resultB = $show_product ->fetch_assoc()){
                                if($resultB['sanpham_host']== 1){
                                    if($resultB['tinhtrang']== '1'){
                          ?>
                       
                            <div class="grid__colum-2-4">
                                <div class="home-product-item">
                                    <a  href="product.php?sanpham_id=<?php echo $resultB['sanpham_id']?>">
                                    <div class="home-product-item_img" style="background-image: url(admin/uploads/<?php echo $resultB['sanpham_anh']?>);"></div></a>
                                    <a href="product.php?sanpham_id=<?php echo $resultB['sanpham_id']?>">
                                    <h4 class="home-product-item_name"><?php echo $resultB['sanpham_tieude']?></h4></a>
                                    <div class="home-product-item_price">
                                        <p style="font-size: 1.4rem; margin: 0 8px;"> Giá:</p>
                                        <?php 
                                         if($resultB['giamgia'] > 0){ 
                                         $TGG = $resultB['sanpham_gia'] * ((100 - $resultB['giamgia'])/100) ?>
                                        <span class="home-product-item_price-old"> <?php $resultA = number_format($resultB['sanpham_gia']); echo $resultA?><sup>đ</sup></span>
                                        <span class="home-product-item_price-current"><?php $resultA = number_format($TGG); echo $resultA?><sup>đ</sup></span>
                                        <?php 
                                       }else { ?>
                                       <span class="home-product-item_price-current"><?php $resultA = number_format($resultB['sanpham_gia']); echo $resultA?><sup>đ</sup></span>
                                       <?php
                                        }
                                       ?>
                                    </div>
                                    <div class="home-product-item_action">
                                        <span class="home-product-item_like home-product-item_like-liked">
                                              <i class="home-product-item_like-icon fa-regular fa-heart"></i>
                                              <i class="home-product-item_like-icon-fill fa-solid fa-heart"></i>
                                        </span>
                                        <img class="home-product-item_img-alt" src="assets/img/hoatoc1.jpg" alt="">
                                    </div>
                                    <div class="home-product-item_origin">
                                         <span class="home-product-item_brand">TEECLUB</span>
                                        <span class="home-product-item_origin-name">Hà Nội</span> 
                                    </div>
                                    <div class="home-product-item_favourite">
                                        <i class="fa-solid fa-check"></i><span> Yêu thích</span>
                                    </div>
                                    <?php if($resultB['giamgia'] > 0){ ?>
                                     <div class="home-product-item_sale-off">
                                     <span class="home-product-item_sale-off-label">GIẢM</span>
                                        <span class="home-product-item_sale-off-percent"> <?php echo $resultB['giamgia']?>%</span>    
                                    </div>
                                    <?php 
                                   }else {
                                   echo '';
                                     }
                                     ?>
                                </div>
                            </div>
                            <?php
                                }}
                              }}
                            ?>
                        </div>
                        <div class="link-product">
                        </div>
                    </div>
                    <div class="home-product-pro">
                        <div class="grid__row">
                            <?php
                            $show_product = $index ->show_product();
                            if($show_product){ while($resultB = $show_product ->fetch_assoc()){
                                if($resultB['sanpham_host']== 2){
                                    if($resultB['tinhtrang']== '1'){
                          ?>
                       
                            <div class="grid__colum-2-4">
                                <div class="home-product-item">
                                    <a  href="product.php?sanpham_id=<?php echo $resultB['sanpham_id']?>">
                                    <div class="home-product-item_img" style="background-image: url(admin/uploads/<?php echo $resultB['sanpham_anh']?>);"></div></a>
                                    <a href="product.php?sanpham_id=<?php echo $resultB['sanpham_id']?>">
                                    <h4 class="home-product-item_name"><?php echo $resultB['sanpham_tieude']?></h4></a>
                                    <div class="home-product-item_price">
                                        <p style="font-size: 1.4rem; margin: 0 8px;"> Giá:</p>
                                        <?php 
                                         if($resultB['giamgia'] > 0){ 
                                         $TGG = $resultB['sanpham_gia'] * ((100 - $resultB['giamgia'])/100) ?>
                                        <span class="home-product-item_price-old"> <?php $resultA = number_format($resultB['sanpham_gia']); echo $resultA?><sup>đ</sup></span>
                                        <span class="home-product-item_price-current"><?php $resultA = number_format($TGG); echo $resultA?><sup>đ</sup></span>
                                        <?php 
                                       }else { ?>
                                       <span class="home-product-item_price-current"><?php $resultA = number_format($resultB['sanpham_gia']); echo $resultA?><sup>đ</sup></span>
                                       <?php
                                        }
                                       ?>
                                    </div>
                                    <div class="home-product-item_action">
                                        <span class="home-product-item_like home-product-item_like-liked">
                                              <i class="home-product-item_like-icon fa-regular fa-heart"></i>
                                              <i class="home-product-item_like-icon-fill fa-solid fa-heart"></i>
                                        </span>
                                        <img class="home-product-item_img-alt" src="assets/img/hoatoc1.jpg" alt="">
                                    </div>
                                    <div class="home-product-item_origin">
                                         <span class="home-product-item_brand">TEECLUB</span>
                                        <span class="home-product-item_origin-name">Hà Nội</span> 
                                    </div>
                                    <div class="home-product-item_favourite">
                                        <i class="fa-solid fa-check"></i><span> Yêu thích</span>
                                    </div>
                                    <?php if($resultB['giamgia'] > 0){ ?>
                                     <div class="home-product-item_sale-off">
                                     <span class="home-product-item_sale-off-label">GIẢM</span>
                                        <span class="home-product-item_sale-off-percent"> <?php echo $resultB['giamgia']?>%</span>    
                                    </div>
                                    <?php 
                                   }else {
                                   echo '';
                                     }
                                     ?>
                                </div>
                            </div>
                            <?php
                                }}
                              }}
                            ?>
                        </div>
                        <div class="link-product">
                        </div>
                    </div>
                     <div class="home-product-produ">
                        <div class="grid__row">
                            <?php
                            $show_product = $index ->show_product();
                            if($show_product){ while($resultB = $show_product ->fetch_assoc()){
                                if($resultB['sanpham_host']== 3){
                                    if($resultB['tinhtrang']== '1'){
                          ?>
                       
                            <div class="grid__colum-2-4">
                                <div class="home-product-item">
                                    <a  href="product.php?sanpham_id=<?php echo $resultB['sanpham_id']?>">
                                    <div class="home-product-item_img" style="background-image: url(admin/uploads/<?php echo $resultB['sanpham_anh']?>);"></div></a>
                                    <a href="product.php?sanpham_id=<?php echo $resultB['sanpham_id']?>">
                                    <h4 class="home-product-item_name"><?php echo $resultB['sanpham_tieude']?></h4></a>
                                    <div class="home-product-item_price">
                                        <p style="font-size: 1.4rem; margin: 0 8px;"> Giá:</p>
                                        <?php 
                                         if($resultB['giamgia'] > 0){ 
                                         $TGG = $resultB['sanpham_gia'] * ((100 - $resultB['giamgia'])/100) ?>
                                        <span class="home-product-item_price-old"> <?php $resultA = number_format($resultB['sanpham_gia']); echo $resultA?><sup>đ</sup></span>
                                        <span class="home-product-item_price-current"><?php $resultA = number_format($TGG); echo $resultA?><sup>đ</sup></span>
                                        <?php 
                                       }else { ?>
                                       <span class="home-product-item_price-current"><?php $resultA = number_format($resultB['sanpham_gia']); echo $resultA?><sup>đ</sup></span>
                                       <?php
                                        }
                                       ?>
                                    </div>
                                    <div class="home-product-item_action">
                                        <span class="home-product-item_like home-product-item_like-liked">
                                              <i class="home-product-item_like-icon fa-regular fa-heart"></i>
                                              <i class="home-product-item_like-icon-fill fa-solid fa-heart"></i>
                                        </span>
                                        <img class="home-product-item_img-alt" src="assets/img/hoatoc1.jpg" alt="">
                                    </div>
                                    <div class="home-product-item_origin">
                                         <span class="home-product-item_brand">TEECLUB</span>
                                        <span class="home-product-item_origin-name">Hà Nội</span> 
                                    </div>
                                    <div class="home-product-item_favourite">
                                        <i class="fa-solid fa-check"></i><span> Yêu thích</span>
                                    </div>
                                    <?php if($resultB['giamgia'] > 0){ ?>
                                     <div class="home-product-item_sale-off">
                                     <span class="home-product-item_sale-off-label">GIẢM</span>
                                        <span class="home-product-item_sale-off-percent"> <?php echo $resultB['giamgia']?>%</span>    
                                    </div>
                                    <?php 
                                   }else {
                                   echo '';
                                     }
                                     ?>
                                </div>
                            </div>
                            <?php
                                }}
                              }}
                            ?>
                        </div>
                        <div class="link-product">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php 
    include "footer.php";
    ?>