<?php
include "../lib/database.php";
require ('../Carbon/autoload.php');
use Carbon\Carbon;

$db = new Database();
$chart_data = []; // Khởi tạo mặc định

// Kiểm tra và làm sạch đầu vào
if (isset($_POST['thoigian'])) {
    $thoigian = $_POST['thoigian'];
    $valid_periods = ['7ngay', '30ngay', '90ngay', '365ngay'];
    if (!in_array($thoigian, $valid_periods)) {
        echo json_encode(['error' => 'Khoảng thời gian không hợp lệ']);
        exit;
    }
} else {
    $thoigian = '365ngay'; // Mặc định 365 ngày
}

// Tính ngày bắt đầu dựa trên khoảng thời gian
$days_map = [
    '7ngay' => 7,
    '30ngay' => 30,
    '90ngay' => 90,
    '365ngay' => 365
];
$subdays = Carbon::now('Asia/Ho_Chi_Minh')->subDays($days_map[$thoigian])->toDateString();
$now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

// Truy vấn sử dụng prepared statement
$query = "SELECT date_thongke, donhang, doanhthu, soluong 
          FROM tbl_thongke 
          WHERE date_thongke BETWEEN ? AND ? 
          ORDER BY date_thongke ASC";
$stmt = $db->link->prepare($query); // Thay $conn thành $link
$stmt->bind_param("ss", $subdays, $now);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $chart_data[] = [
        'date' => $row['date_thongke'],
        'order' => (int)$row['donhang'],
        'revenue' => (int)$row['doanhthu'],
        'soluong' => (int)$row['soluong']
    ];
}
$stmt->close();

// Trả về dữ liệu JSON
if (empty($chart_data)) {
    echo json_encode(['error' => 'Không có dữ liệu thống kê cho khoảng thời gian này']);
} else {
    echo json_encode($chart_data);
}
?>