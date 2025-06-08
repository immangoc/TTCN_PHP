<?php
date_default_timezone_set('Asia/Ho_Chi_Minh'); 
$vnp_TmnCode = "98WG8EFQ"; 
$vnp_HashSecret = "8QFWT0MNHQDPFVP86NRAFFSHT6ZC89KW"; 
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://localhost/TTCN_PHP/htdocs/camon.php";
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
$apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";
$startTime = date("YmdHis");
$expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));
?>
