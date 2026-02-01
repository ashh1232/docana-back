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
    if ($action === 'add_new_vendor') {
        $userId =  sanitizeInput($_POST['user_id']);
        $data = array(
    "user_role" => "vendor",
    "user_status" => 2,
);
        updateData("users", $data, "user_id = $userId");
    } elseif ($action === 'get_all_admin_orders') {
        getAllData("users", "user_status = '2' AND user_role = 'vendor'");
    } elseif ($action === 'get_order_details') {
        getAllData("users");
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
