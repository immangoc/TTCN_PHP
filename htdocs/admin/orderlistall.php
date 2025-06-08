<?php
include "header.php";
include "leftside.php";
include "class/product_class.php";
include "helper/format.php";
$product = new product();
$fm = new Format();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đơn hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>

        .order-status.status-0 {
            background-color: #FFFF99; 
        }
        .order-status.status-1 {
            background-color: #99FF99; 
        }
        .order-status {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 14px;
            width: 120px;
            cursor: pointer;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .order-status:hover {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }
        .order-status:focus {
            outline: none;
            border-color: #007bff;
        }

       
    </style>
</head>
<body>
    <main class="app-content">
        <div class="app-title">
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <div class="row element-button">
                            <h3>Tất cả các đơn hàng:</h3>
                        </div>
                        <table id="customers">
                            <tr>
                                <th>STT</th>
                                <th>Mã đơn hàng</th>
                                <th>Ngày đặt hàng</th>
                                <th>ID khách hàng</th>
                                <th>Thông tin khách hàng</th>
                                <th>Giao hàng</th>
                                <th>Thanh toán</th>
                                <th>Chi tiết đơn hàng</th>
                                <th>Tình trạng</th>
                                <th>Chức năng</th>
                            </tr>
                            <?php
                            $show_orderAll = $product->show_orderAll();
                            if ($show_orderAll) {
                                $i = 0;
                                while ($result = $show_orderAll->fetch_assoc()) {
                                    $i++;
                            ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $result['code_oder'] ?></td>
                                <td><?php echo $result['order_date'] ?></td>
                                <td><?php echo $result['register_id'] ?></td>
                                <td class="td-list"><a href="register.php?registerid=<?php echo $result['register_id'] ?>">Xem</a></td>
                                <td><?php echo $result['giaohang'] ?></td>
                                <td><?php echo $result['thanhtoan'] ?></td>
                                <td class="td-list"><a href="orderdetail.php?order_ma=<?php echo $result['session_idA'] ?>">Xem</a></td>
                                <td>
                                    <select class="order-status status-<?php echo $result['statusA'] ?>" data-session-id="<?php echo $result['session_idA'] ?>">
                                        <option value="0" <?php if ($result['statusA'] == '0') echo 'selected'; ?>>Đang xử lý</option>
                                        <option value="1" <?php if ($result['statusA'] == '1') echo 'selected'; ?>>Đã xử lý</option>
                                    </select>
                                </td>
                                <td>
                                    <a href="orderdelete.php?session_idA=<?php echo $result['session_idA'] ?>" 
                                       onclick="return confirm('Đơn hàng sẽ bị xóa vĩnh viễn, bạn có chắc muốn tiếp tục không?');" 
                                       class="btn btn-edit" 
                                       title="Xóa">
                                       <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                                }
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        function updateStatusColor(select) {
            var status = $(select).val();
            $(select).removeClass('status-0 status-1');
            if (status == '0') {
                $(select).addClass('status-0'); 
            } else if (status == '1') {
                $(select).addClass('status-1'); 
            }
        }

        $('.order-status').each(function() {
            updateStatusColor(this);
        });

        $('.order-status').change(function() {
            var $select = $(this);
            var sessionId = $select.data('session-id');
            var status = $select.val();

            if (!sessionId || (status !== '0' && status !== '1')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Dữ liệu không hợp lệ. Vui lòng thử lại.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            updateStatusColor($select);

            $.ajax({
                url: "ajax/update_order_status.php",
                type: 'POST',
                dataType: 'json',
                data: {
                    session_idA: sessionId,
                    status: status
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: 'Cập nhật trạng thái đơn hàng thành công!',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Không thể cập nhật trạng thái: ' + response.message,
                            confirmButtonText: 'OK'
                        });
                        $select.val(status === '0' ? '1' : '0');
                        updateStatusColor($select);
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Lỗi kết nối server: ' + error,
                        confirmButtonText: 'OK'
                    });
                    $select.val(status === '0' ? '1' : '0');
                    updateStatusColor($select);
                }
            });
        });
    });
    </script>
</body>
</html>