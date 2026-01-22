<?php
// order.php - Order Management API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
include "../connect.php"; // Make sure this file exists with your DB connection

// Get the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Handle different request types
if ($requestMethod === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    // $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    if ($action === 'get_order_items') {
        $order = filterRequest('order_id');
        getAllData('order_items', "order_id = $order");
    } elseif ($action === 'get_process_order') {
        getAllData('order', "order_status = 'processing'");
    } elseif ($action === 'process_order') {
        $orderid = filterRequest('order_id');
        $data = array(
            "order_status" => "processing",
        );
        updateData("orders", $data, "order_id = $orderid");
    } elseif ($action === 'get_order_details') {
        $orderid = filterRequest('order_id');
        $data = array(
            "order_status" => "processing",
        );
        updateData("orders", $data, "order_id = $orderid");
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
