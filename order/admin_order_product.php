<?php
include "../connect.php"; // Make sure this file exists with your DB connection
header('Content-Type: application/json');
$order=filterRequest('order_id');
getAllData('order_items', "order_id = $order");