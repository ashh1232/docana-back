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
    if ($action === 'get_pending_order') {
        getAllData('orders', "order_status = 'pending'");
    } elseif ($action === 'get_processing_order') {
        getAllData('orders', "order_status = 'processing'");
        // Admin Users
    } elseif ($action === 'is_admin') {
        $usrId = filterRequest('usr_id');

        // جلب بيانات المستخدم للتأكد من رتبته وحالته
        $userData = getAllData('users', "user_id = '$usrId' AND user_role = 'vendor' AND user_status = '1'", null, false);


        if ($userData['status'] == 'success') {
            //     // جلب بيانات البائع الإضافية من جدول vendors
            $vendorDetails = getAllData('vendors', "user_id = '$usrId'", null, false);
            //                 // echo json_encode(array("status" => "success", "data" => $userData));

            echo json_encode([
                'status' => 'success',
                'is_admin' => true,
                // 'user_data' => $userData, // بيانات الجدول users
                'vendor_data' => $vendorDetails['data'][0]['vendor_id'] // بيانات الجدول vendors
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'is_admin' => false,
                'message' => 'اللمستخدم ليس بائعاً أو غير نشط'
            ]);
        }
    } elseif ($action === 'get_order_items') {
        $order = filterRequest('order_id');
        $vendorId = filterRequest('vendor_id');
        getAllData('order_items', "order_id = $order AND vendor_id = $vendorId");
    } elseif ($action === 'process_order') {
        $orderid = filterRequest('order_id');
        $data = array(
            "order_status" => "processing",
        );
        updateData("orders", $data, "order_id = $orderid");
        updateData("order_items", $data, "order_id = $orderid AND vendor_id = 'pending'");
    } elseif ($action === 'get_order_details') {
        $orderid = filterRequest('order_id');
        $data = array(
            "order_status" => "processing",
        );
        updateData("orders", $data, "order_id = $orderid");
    } elseif ($action === 'get_pending_order_vendor') {
        getVendorOrders($con);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

function getVendorOrders($con)
{
    $vendorId = $_POST['vendor_id'];

    // استعلام يجلب الطلبات الفريدة التي تحتوي على منتج واحد على الأقل لهذا التاجر
    $sql = "SELECT DISTINCT o.* 
            FROM orders o
            JOIN order_items oi ON o.order_id = oi.order_id
            WHERE oi.vendor_id = ? AND oi.order_status = 'pending'
            ORDER BY o.created_at DESC";

    $stmt = $con->prepare($sql);
    $stmt->execute([$vendorId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($orders) {
        // لكل طلب، سنجلب فقط المنتجات الخاصة بهذا التاجر
        foreach ($orders as &$order) {
            $itemSql = "SELECT * FROM order_items WHERE order_id = ? AND vendor_id = ?";
            $itemStmt = $con->prepare($itemSql);
            $itemStmt->execute([$order['order_id'], $vendorId]);
            $order['my_items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode(['status' => 'success', 'data' => $orders]);
    } else {
        echo json_encode(['status' => 'success', 'data' => [], 'message' => 'لا توجد طلبات حالياً']);
    }
}
