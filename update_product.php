<?php







include "./connect.php";



$id       = filterRequest('id');

$name     = filterRequest('name');

$desc     = filterRequest('desc');

$image    = filterRequest('image');

$image2   = filterRequest('image2');

$image3   = filterRequest('image3');

$price    = filterRequest('price');

$count    = filterRequest('count');

$active   = filterRequest('active');

$discount = filterRequest('discount');



$data = array(

    "product_name" => $name,

    "product_desc" => $desc,

    "product_image" => $image,

    "product_image2" => $image2,

    "product_image3" => $image3,

    "product_price" => $price,

    "product_count" => $count,

    "product_active" => $active,

    "product_discount" => $discount,

);



updateData("products", $data, "product_id =$id");
