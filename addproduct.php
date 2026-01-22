<?php

include './connect.php';


$name = filterRequest('name');

$desc = filterRequest('desc');

$price = filterRequest('price');

$discount = filterRequest('discount');

$image1 = filterRequest('image1');

$image2 = filterRequest('image2');

$image3 = filterRequest('image3');



$data = array(

    'product_name' => $name,

    'product_desc' => $desc,

    'product_price' => $price,

    'product_discount' => $discount,

    'product_image' => $image1,

    'product_image2' => $image2,

    'product_image3' => $image3,

    'product_image3' => $image3,

    'product_cat' => '15'

);

insertData('products', $data);
