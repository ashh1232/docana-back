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
    $cat=filterRequest('cat_id');
    // $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    if ($action === 'get_cat') {
        getAllData('categories');
    } elseif ($action === 'get_cat_id') {
        
        getAllData('productview', "categories_id = $cat");
    } elseif ($action === 'get_ban_cat') {
        getAllData('banners', "banner_cat = $cat");
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
