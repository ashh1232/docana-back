<?php

include "connect.php";

$alldata = array();

$alldata["status"] = "success";


$categories = getAllData("categories", null, null, false);
$alldata["categories"] = $categories;


$products = getAllData("productview", null, null, false);
$alldata["productview"] = $products;


// sdfsd


echo json_encode($alldata);
