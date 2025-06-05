<?php
include "header.php";
include "leftside.php"; 
include "class/product_class.php";
$product = new product();
if (isset($_GET['sanpham_id']) && !empty($_GET['sanpham_id'])) {
    $sanpham_id = $_GET['sanpham_id'];
    $get_sanpham = $product->get_sanpham($sanpham_id);
    if ($get_sanpham) {
        $resul = $get_sanpham->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $update_product = $product->update_product($_POST, $_FILES, $sanpham_id);
    if ($update_product) {
        header('Location: Sanphamlist.php');
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .app-content { padding: 2rem; }
        .tile { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 2rem; }
        .title-h3 { color: #343a40; font-weight: 600; margin-bottom: 1.5rem; }
        .control-label { font-weight: 500; color: #495057; }
        .form-control, .form-select { border-radius: 6px; border: 1px solid #ced4da; padding: 0.5rem; }
        .form-control:focus, .form-select:focus { border-color: #007bff; box-shadow: 0 0 5px rgba(0,123,255,0.2); }
        .btn-save { background-color: #007bff; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; color: #fff; }
        .btn-save:hover { background-color: #0056b3; }
        .btn-cancel { border-color: #6c757d; color: #6c757d; padding: 0.75rem 1.5rem; border-radius: 6px; }
        .btn-cancel:hover { background-color: #6c757d; color: #fff; }
        .sanpham-size { display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: center; }
        .sanpham-size .form-check { display: flex; align-items: center; gap: 0.75rem; min-width: 60px; }
        .sanpham-size .form-check-input { width: 1.25rem; height: 1.25rem; margin: 0; }
        .sanpham-size .form-check-label { font-weight: normal; color: #495057; }
        .section-title { font-size: 1.1rem; font-weight: 500; color: #343a40; margin: 1.5rem 0 1rem; border-bottom: 1px solid #e9ecef; padding-bottom: 0.5rem; }
        .from-group { margin-bottom: 1.25rem; }
        .error-message { color: #dc3545; font-size: 0.875rem; }
        .sanpham-anh img { margin-right: 10px; margin-bottom: 10px; }
        @media (max-width: 768px) {
            .app-content { padding: 1rem; }
            .tile { padding: 1rem; }
            .from-group { margin-bottom: 1rem; }
            .sanpham-size { gap: 1rem; }
            .sanpham-size .form-check { min-width: 50px; }
        }
    </style>
</head>
<body>
<main class="app-content">
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="title-h3">Sửa sản phẩm</h3>
                <div class="tile-body">
                    <div class="admin-content-right">
                        <?php if (isset($update_product)): ?>
                            <div class="error-message"><?php echo htmlspecialchars($update_product); ?></div>
                        <?php endif; ?>
                        <form action="Sanphamedit.php" method="POST" enctype="multipart/form-data">
                            <div class="product-add-content">
                                <div class="section-title">Thông tin cơ bản</div>
                                <div class="row">
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="sanpham_tieude">Tên sản phẩm <span class="text-danger">*</span></label>
                                        <input class="form-control" value="<?php echo htmlspecialchars($resul['sanpham_tieude']); ?>" required type="text" name="sanpham_tieude" id="sanpham_tieude">
                                    </div>
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="sanpham_ma">Mã sản phẩm <span class="text-danger">*</span></label>
                                        <input class="form-control" value="<?php echo htmlspecialchars($resul['sanpham_ma']); ?>" required type="text" name="sanpham_ma" id="sanpham_ma">
                                    </div>
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="danhmuc_id">Chọn danh mục <span class="text-danger">*</span></label>
                                        <select class="form-control" required name="danhmuc_id" id="danhmuc_id">
                                            <option value="">--Chọn--</option>
                                            <?php
                                            $show_danhmuc = $product->show_danhmuc();
                                            if ($show_danhmuc) {
                                                while ($result = $show_danhmuc->fetch_assoc()) {
                                                    $selected = ($resul['danhmuc_id'] == $result['danhmuc_id']) ? 'selected' : '';
                                                    echo '<option value="' . htmlspecialchars($result['danhmuc_id']) . '" ' . $selected . '>' . htmlspecialchars($result['danhmuc_ten']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="loaisanpham_id">Chọn Loại sản phẩm <span class="text-danger">*</span></label>
                                        <select class="form-control" required name="loaisanpham_id" id="loaisanpham_id">
                                            <option value="">--Chọn--</option>
                                            <?php
                                            $show_loaisanpham = $product->show_loaisanpham();
                                            if ($show_loaisanpham) {
                                                while ($result = $show_loaisanpham->fetch_assoc()) {
                                                    $selected = ($resul['loaisanpham_id'] == $result['loaisanpham_id']) ? 'selected' : '';
                                                    echo '<option value="' . htmlspecialchars($result['loaisanpham_id']) . '" ' . $selected . '>' . htmlspecialchars($result['loaisanpham_ten']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="section-title">Thuộc tính sản phẩm</div>
                                <div class="row">
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="color_id">Chọn Màu sản phẩm <span class="text-danger">*</span></label>
                                        <select class="form-control" required name="color_id" id="color_id">
                                            <option value="">--Chọn--</option>
                                            <?php
                                            $show_color = $product->show_color();
                                            if ($show_color) {
                                                while ($result = $show_color->fetch_assoc()) {
                                                    $selected = ($resul['color_id'] == $result['color_id']) ? 'selected' : '';
                                                    echo '<option value="' . htmlspecialchars($result['color_id']) . '" ' . $selected . '>' . htmlspecialchars($result['color_ten']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="from-group col-md-6">
                                        <label class="control-label">Chọn Size sản phẩm <span class="text-danger">*</span></label>
                                        <div class="sanpham-size">
                                            <?php
                                            $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
                                            $selected_sizes = explode(',', $resul['sanpham_size'] ?? ''); // Giả định size lưu trong cột sanpham_size, dạng chuỗi
                                            foreach ($sizes as $size) {
                                                $checked = in_array($size, $selected_sizes) ? 'checked' : '';
                                                echo '
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sanpham-size[]" value="' . $size . '" id="size_' . strtolower($size) . '" ' . $checked . '>
                                                    <label class="form-check-label" for="size_' . strtolower($size) . '">' . $size . '</label>
                                                </div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="soluong">Số lượng <span class="text-danger">*</span></label>
                                        <input class="form-control" value="<?php echo htmlspecialchars($resul['soluong']); ?>" required type="text" name="soluong" id="soluong">
                                    </div>
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="giamgia">Giảm giá <span class="text-danger">*</span></label>
                                        <input class="form-control" value="<?php echo htmlspecialchars($resul['giamgia']); ?>" required type="text" name="giamgia" id="giamgia">
                                    </div>
                                </div>

                                <div class="section-title">Thông tin bổ sung</div>
                                <div class="row">
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="sanpham_gia">Giá sản phẩm <span class="text-danger">*</span></label>
                                        <input class="form-control" value="<?php echo htmlspecialchars($resul['sanpham_gia']); ?>" required type="text" name="sanpham_gia" id="sanpham_gia">
                                    </div>
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="tinhtrang">Tình trạng <span class="text-danger">*</span></label>
                                        <select class="form-control" required name="tinhtrang" id="tinhtrang">
                                            <option value="">--Chọn--</option>
                                            <option value="1" <?php if ($resul['tinhtrang'] == 1) echo 'selected'; ?>>Còn Hàng</option>
                                            <option value="0" <?php if ($resul['tinhtrang'] == 0) echo 'selected'; ?>>Hết Hàng</option>
                                        </select>
                                    </div>
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="sanpham_host">Sản phẩm host <span class="text-danger">*</span></label>
                                        <select class="form-control" required name="sanpham_host" id="sanpham_host">
                                            <option value="">--Chọn--</option>
                                            <option value="1" <?php if ($resul['sanpham_host'] == 1) echo 'selected'; ?>>GIRL</option>
                                            <option value="2" <?php if ($resul['sanpham_host'] == 2) echo 'selected'; ?>>MEN</option>
                                            <option value="3" <?php if ($resul['sanpham_host'] == 3) echo 'selected'; ?>>KIDS</option>
                                            <option value="0" <?php if ($resul['sanpham_host'] == 0) echo 'selected'; ?>>Khác</option>
                                        </select>
                                    </div>
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="sapxepsp">Sắp xếp sản phẩm <span class="text-danger">*</span></label>
                                        <select class="form-control" required name="sapxepsp" id="sapxepsp">
                                            <option value="">--Chọn--</option>
                                            <option value="0" <?php if ($resul['sapxepsp'] == 0) echo 'selected'; ?>>Mới nhất</option>
                                            <option value="1" <?php if ($resul['sapxepsp'] == 1) echo 'selected'; ?>>Bán chạy</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="section-title">Hình ảnh sản phẩm</div>
                                <div class="row">
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="sanpham_anh">Ảnh đại diện <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" name="sanpham_anh" id="sanpham_anh">
                                        <img style="width: 100px; height: 100px; margin-top: 10px;" src="Uploads/<?php echo htmlspecialchars($resul['sanpham_anh']); ?>" alt="Ảnh đại diện">
                                    </div>
                                    <div class="from-group col-md-3">
                                        <label class="control-label" for="sanpham_anhs">Ảnh sản phẩm mô tả <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" multiple name="sanpham_anhs[]" id="sanpham_anhs">
                                        <div class="sanpham-anh mt-2">
                                            <?php
                                            $get_anh = $product->get_anh($sanpham_id);
                                            if ($get_anh) {
                                                while ($resultA = $get_anh->fetch_assoc()) {
                                                    echo '<img style="width: 100px; height: 100px;" src="Uploads/' . htmlspecialchars($resultA['sanpham_anh']) . '" alt="Ảnh mô tả">';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-title">Mô tả sản phẩm</div>
                                <div class="from-group col-12">
                                    <label class="control-label" for="sanpham_gioithieu">Giới thiệu <span class="text-danger">*</span></label>
                                    <textarea class="form-control" required name="sanpham_gioithieu" id="sanpham_gioithieu" cols="60" rows="5"><?php echo htmlspecialchars($resul['sanpham_gioithieu']); ?></textarea>
                                </div>
                                <div class="from-group col-12">
                                    <label class="control-label" for="sanpham_chitiet">Chi tiết <span class="text-danger">*</span></label>
                                    <textarea class="form-control" required name="sanpham_chitiet" id="sanpham_chitiet" cols="60" rows="5"><?php echo htmlspecialchars($resul['sanpham_chitiet']); ?></textarea>
                                </div>
                                <div class="from-group col-12">
                                    <label class="control-label" for="sanpham_baoquan">Bảo quản <span class="text-danger">*</span></label>
                                    <textarea class="form-control" required name="sanpham_baoquan" id="sanpham_baoquan" cols="60" rows="5"><?php echo htmlspecialchars($resul['sanpham_baoquan']); ?></textarea>
                                </div>

                                <div class="from-group col-12 text-end mt-3">
                                    <button class="btn btn-save" name="submit" type="submit">
                                        <i class="fas fa-save me-1"></i> Sửa
                                    </button>
                                    <a href="Sanphamlist.php" class="btn btn-cancel">
                                        <i class="fas fa-times me-1"></i> Hủy bỏ
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#danhmuc_id").change(function(){
                var x = $(this).val();
                $.get("ajax/productadd_ajax.php", {danhmuc_id: x}, function(data) {
                    $("#loaisanpham_id").html(data);
                });
            });
        });
    </script>
    <script src="js/main.js"></script>
</body>
</html>