<?php
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

// Function to create a new order
function createOrder($con) {
    try {
        // Get POST data
        $userId = mysqli_real_escape_string($con, $_POST['user_id']);
        $subtotal = mysqli_real_escape_string($con, $_POST['subtotal']);
        $tax = mysqli_real_escape_string($con, $_POST['tax']);
        $shipping = mysqli_real_escape_string($con, $_POST['shipping']);
        $total = mysqli_real_escape_string($con, $_POST['total']);
        
        // Delivery information
        $deliveryName = mysqli_real_escape_string($con, $_POST['delivery_name']);
        $deliveryPhone = mysqli_real_escape_string($con, $_POST['delivery_phone']);
        $deliveryAddress = mysqli_real_escape_string($con, $_POST['delivery_address']);
        $deliveryCity = mysqli_real_escape_string($con, $_POST['delivery_city']);
        $deliveryCountry = mysqli_real_escape_string($con, $_POST['delivery_country']);
        
        // Payment information
        $paymentMethod = mysqli_real_escape_string($con, $_POST['payment_method']);
        $orderNotes = isset($_POST['order_notes']) ? mysqli_real_escape_string($con, $_POST['order_notes']) : '';
        
        // Order items (JSON string)
        $orderItems = isset($_POST['order_items']) ? $_POST['order_items'] : '';
        
        // Validate required fields
        if (empty($userId) || empty($total) || empty($deliveryName) || empty($deliveryPhone) || empty($deliveryAddress)) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            return;
        }
        
        // Start transaction
        mysqli_begin_transaction($con);
        
        // Insert order
        $sql = "INSERT INTO orders (
            user_id, order_total, order_subtotal, order_tax, order_shipping,
            delivery_name, delivery_phone, delivery_address, delivery_city, delivery_country,
            payment_method, order_notes
        ) VALUES (
            '$userId', '$total', '$subtotal', '$tax', '$shipping',
            '$deliveryName', '$deliveryPhone', '$deliveryAddress', '$deliveryCity', '$deliveryCountry',
            '$paymentMethod', '$orderNotes'
        )";
        
        if (mysqli_query($con, $sql)) {
            $orderId = mysqli_insert_id($con);
            
            // Insert order items
            $items = json_decode($orderItems, true);
            if (is_array($items)) {
                foreach ($items as $item) {
                    $productId = mysqli_real_escape_string($con, $item['product_id']);
                    $productName = mysqli_real_escape_string($con, $item['product_name']);
                    $productImage = mysqli_real_escape_string($con, $item['product_image']);
                    $productPrice = mysqli_real_escape_string($con, $item['product_price']);
                    $quantity = mysqli_real_escape_string($con, $item['quantity']);
                    $itemTotal = $productPrice * $quantity;
                    
                    $itemSql = "INSERT INTO order_items (
                        order_id, product_id, product_name, product_image, product_price, item_quantity, item_total
                    ) VALUES (
                        '$orderId', '$productId', '$productName', '$productImage', '$productPrice', '$quantity', '$itemTotal'
                    )";
                    
                    if (!mysqli_query($con, $itemSql)) {
                        mysqli_rollback($con);
                        echo json_encode(['status' => 'error', 'message' => 'Failed to insert order items']);
                        return;
                    }
                }
            }
            
            // Commit transaction
            mysqli_commit($con);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Order created successfully',
                'order_id' => $orderId
            ]);
        } else {
            mysqli_rollback($con);
            echo json_encode(['status' => 'error', 'message' => 'Failed to create order: ' . mysqli_error($con)]);
        }
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo json_encode(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
    }
}

// Function to get user orders
function getUserOrders($con) {
    $userId = mysqli_real_escape_string($con, $_POST['user_id']);
    
    $sql = "SELECT 
        o.order_id, o.order_total, o.order_status, o.payment_method, o.created_at,
        COUNT(oi.item_id) as item_count
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.user_id = '$userId'
    GROUP BY o.order_id
    ORDER BY o.created_at DESC";
    
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        $orders = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $orders]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch orders']);
    }
}

// Function to get order details
function getOrderDetails($con) {
    $orderId = mysqli_real_escape_string($con, $_POST['order_id']);
    
    // Get order info
    $orderSql = "SELECT * FROM orders WHERE order_id = '$orderId'";
    $orderResult = mysqli_query($con, $orderSql);
    
    if ($orderResult && mysqli_num_rows($orderResult) > 0) {
        $order = mysqli_fetch_assoc($orderResult);
        
        // Get order items
        $itemsSql = "SELECT * FROM order_items WHERE order_id = '$orderId'";
        $itemsResult = mysqli_query($con, $itemsSql);
        
        $items = [];
        while ($row = mysqli_fetch_assoc($itemsResult)) {
            $items[] = $row;
        }
        
        $order['items'] = $items;
        
        echo json_encode(['status' => 'success', 'data' => $order]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Order not found']);
    }
}
?>
