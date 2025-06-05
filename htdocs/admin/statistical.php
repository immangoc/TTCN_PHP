<?php
include "header.php";
include "leftside.php";
include "class/product_class.php";
include "helper/format.php";
$product = new product();
$fm = new Format();

require ('Carbon/autoload.php');
use Carbon\Carbon;
use Carbon\CarbonInterval;
?>

<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Báo cáo thống kê doanh thu</b></a></li>
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
                        <div id="chart" style="height: 250px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // Khởi tạo biểu đồ Chart.js
        let revenueChart = new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Số đơn hàng',
                        data: [],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Doanh thu',
                        data: [],
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Số lượng',
                        data: [],
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN');
                            }
                        }
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label === 'Doanh thu') {
                                    return label + ': ' + context.parsed.y.toLocaleString('vi-VN') + ' VNĐ';
                                }
                                return label + ': ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });

        // Tải dữ liệu mặc định (365 ngày)
        function day365() {
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
                    if (data.error) {
                        $('#chart').html('<div>' + data.error + '</div>');
                        return;
                    }
                    $('#chart').html('<canvas id="revenueChart"></canvas>');
                    revenueChart.destroy();
                    revenueChart = new Chart(document.getElementById('revenueChart').getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: data.map(item => item.date),
                            datasets: [
                                {
                                    label: 'Số đơn hàng',
                                    data: data.map(item => item.order),
                                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Doanh thu',
                                    data: data.map(item => item.revenue),
                                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Số lượng',
                                    data: data.map(item => item.soluong),
                                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString('vi-VN');
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: { position: 'top' },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label === 'Doanh thu') {
                                                return label + ': ' + context.parsed.y.toLocaleString('vi-VN') + ' VNĐ';
                                            }
                                            return label + ': ' + context.parsed.y;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    $('#chart').html('<div>Lỗi tải dữ liệu. Vui lòng thử lại.</div>');
                }
            });
        }

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
            
            if (thoigian) {
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
                        if (data.error) {
                            $('#chart').html('<div>' + data.error + '</div>');
                            return;
                        }
                        $('#chart').html('<canvas id="revenueChart"></canvas>');
                        revenueChart.destroy();
                        revenueChart = new Chart(document.getElementById('revenueChart').getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: data.map(item => item.date),
                                datasets: [
                                    {
                                        label: 'Số đơn hàng',
                                        data: data.map(item => item.order),
                                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Doanh thu',
                                        data: data.map(item => item.revenue),
                                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Số lượng',
                                        data: data.map(item => item.soluong),
                                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return value.toLocaleString('vi-VN');
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    legend: { position: 'top' },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.dataset.label || '';
                                                if (label === 'Doanh thu') {
                                                    return label + ': ' + context.parsed.y.toLocaleString('vi-VN') + ' VNĐ';
                                                }
                                                return label + ': ' + context.parsed.y;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        $('#chart').html('<div>Lỗi tải dữ liệu. Vui lòng thử lại.</div>');
                    }
                });
            }
        });

        // Gọi hàm tải dữ liệu mặc định
        day365();
    });
</script>
</body>
</html>