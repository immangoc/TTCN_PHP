<?php
include "header.php";
include "leftside.php";
include "class/product_class.php";
include "helper/format.php";
$product = new product();
$fm = new Format();

require ('Carbon/autoload.php');
   use Carbon\Carbon;
   use carbon\CarbonInterval;
?>

      <main class="app-content">
        <div class="app-title">
            <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Báo cáo thống kê doanh thu</b></a></li>
                <!-- <li class="breadcrumb-item active"><a href="binhluandone.php"><b>Đã kiểm tra</b></a></li>
                <li class="breadcrumb-item active"><a href="binhluanlist.php"><b>Chưa kiểm tra</b></a></li> -->
                
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <div class="row element-button">
                           <h3>Báo cáo thống kê doanh thu: <?php echo Carbon::now('Asia/Ho_Chi_Minh'); ?></h3>
                        </div>
                        <div class="admin-content-right">
                            <div class="product-add-content git">
                                      <div class="from-group col-md-3">
                                        <label class="control-label" for="">Lọc theo: <span id="text-date" style="color: red;">*</span></label> <br>
                                        <select class="form-control select-thongke">
                                        <option value="">--Chọn--</option>
                                        <option value="7ngay">-- Lọc theo 7 ngày ---</option>
                                        <option value="30ngay">-- Lọc theo 30 ngày ---</option>
                                        <option value="90ngay">-- Lọc theo 90 ngày ---</option>
                                        <option value="365ngay">-- Lọc theo 1 năm ---</option>
                                        </select>
                                      </div>
                            </div>
                        </div>
                        <div>
                        <div id="chart" style="height: 250px;"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // Khởi tạo biểu đồ cột Morris.js
        var chars = new Morris.Bar({
            element: 'chart',
            parseTime: false,
            xkey: 'date',
            ykeys: ['order', 'revenue', 'soluong'],
            labels: ['Số đơn hàng', 'Doanh thu', 'Số lượng']
        });

        // Tải dữ liệu mặc định (365 ngày)
        day365();

        // Xử lý sự kiện thay đổi dropdown
        $('.select-thongke').change(function(){
            var thoigian = $(this).val();
            var text = '';
            if(thoigian === '7ngay'){
                text = '7 ngày qua';
            } else if(thoigian === '30ngay'){
                text = '30 ngày qua';
            } else if(thoigian === '90ngay'){
                text = '90 ngày qua';
            } else if(thoigian === '365ngay'){
                text = '365 ngày qua';
            } else {
                text = 'Vui lòng chọn khoảng thời gian';
            }

            $('#text-date').text(text);
            
            if (thoigian) { // Chỉ gửi AJAX nếu có giá trị thoigian
                $.ajax({
                    url: "ajax/thongke.php",
                    type: "POST",
                    dataType: "JSON",
                    cache: false,
                    data: { thoigian: thoigian },
                    beforeSend: function() {
                        $('#chart').html('<div>Đang tải...</div>');
                    },
                    success: function(data) {
                        chars.setData(data);
                    },
                    error: function(xhr, status, error) {
                        $('#chart').html('<div>Lỗi tải dữ liệu. Vui lòng thử lại.</div>');
                    }
                });
            }
        });

        // Hàm tải dữ liệu mặc định (365 ngày)
        function day365(){
            var text = '365 ngày qua';
            $('#text-date').text(text);
            $.ajax({
                url: "ajax/thongke.php",
                type: "POST",
                dataType: "JSON",
                cache: false,
                data: { thoigian: '365ngay' },
                beforeSend: function() {
                    $('#chart').html('<div>Đang tải...</div>');
                },
                success: function(data) {
                    chars.setData(data);
                },
                error: function(xhr, status, error) {
                    $('#chart').html('<div>Lỗi tải dữ liệu. Vui lòng thử lại.</div>');
                }
            });
        }
    });
</script>
</body>
</html>