<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// rest of your code...

// order.php - Order Management API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
include "connect.php"; // Make sure this file exists with your DB connection

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





function createOrder($con) {
    try {
        // Get POST data - No need for manual escaping anymore!
        $userId = $_POST['user_id'];
        $total = $_POST['total'];
        $subtotal = $_POST['subtotal'];
        $shipping = $_POST['shipping'];
        $deliveryName = $_POST['delivery_name'];
        $deliveryPhone = $_POST['delivery_phone'];
        $deliveryAddress = $_POST['delivery_address'];
        $deliveryCity = $_POST['delivery_city'];
        // $deliveryCountry = $_POST['delivery_country'];
        $orderItems = $_POST['order_items'] ?? '';

        // Start transaction
        $con->beginTransaction();

        // 1. Insert order using Prepared Statements
        $sql = "INSERT INTO orders (
            user_id, order_total, order_subtotal, order_shipping,
            delivery_name, delivery_phone, delivery_address, delivery_city,
             order_notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $userId, $total, $subtotal, $shipping,
            $deliveryName, $deliveryPhone, $deliveryAddress, $deliveryCity,
            $orderNotes
        ]);

        $orderId = $con->lastInsertId();

        // 2. Insert order items
        $items = json_decode($orderItems, true);
        if (is_array($items)) {
            $itemSql = "INSERT INTO order_items (
                order_id, product_id, product_name, product_image, product_price, item_quantity, item_total
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $itemStmt = $con->prepare($itemSql);

            foreach ($items as $item) {
                $itemTotal = $item['product_price'] * $item['quantity'];
                $itemStmt->execute([
                    $orderId, $item['product_id'], $item['product_name'], 
                    $item['product_image'], $item['product_price'], 
                    $item['quantity'], $itemTotal
                ]);
            }
        }

        $con->commit();
        echo json_encode(['status' => 'success', 'order_id' => $orderId]);

    } catch (Exception $e) {
        $con->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
