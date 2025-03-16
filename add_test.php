<?php

include './connect.php';



$name = filterRequest('name');

// $desc = filterRequest('desc');

$price = filterRequest('price');

// $discount = filterRequest('discount');

$image = filterRequest('image');

// $image2 = filterRequest('image2');

$proCat = filterRequest('product_cat');



$data = array(

    'product_name' => $name,

    // 'product_desc' => $desc,

    'product_price' => $price,

    // 'product_discount' => $discount,

    'product_image' => $image,

    // 'product_image2' => $image2,

    // 'product_image3' => $image3,

    // 'product_image3' => $image3,

    'product_cat' => $proCat

);

insertData('products',$data);