<?php

// order.php - Order Management API

// Database configuration
include "../connect.php"; // Make sure this file exists with your DB connection

// Get the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Handle different request types
if ($requestMethod === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    // $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    if ($action === 'create_order') {
        createOrder($con);
    } elseif ($action === 'get_orders') {
        getUserOrders($con);
    } elseif ($action === 'get_order_details') {
        getOrderDetails($con);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
