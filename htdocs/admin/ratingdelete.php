<?php
include "header.php";
include "leftside.php";
include "class/product_class.php";
include "helper/format.php";
$product = new product();
$fm = new Format();
?>
<?php
if (isset($_GET['id'])|| $_GET['id']!=NULL){
    $id = $_GET['id'];
    }
    $delete_rating = $product -> delete_rating($id);
    header('Location:rating.php');
?>
