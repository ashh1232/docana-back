<?php
// استقبال بيانات JSON القادمة من Node.js
$json = file_get_contents('php://input');
$data = json_decode($json, true);
include './connect.php';


if ($data) {


    $dataa = array(
        'product_name' => $data['name'],
        'product_price' => $data['price'],
        'product_image' => $data['img'],
    );

    insertData('products', $dataa);

    // الاتصال بـ MySQL (تأكد من اسم القاعدة والمستخدم)
    $db = new PDO('mysql:host=localhost;dbname=shein_db;charset=utf8', 'root', '');

    // استعلام الإدخال أو التحديث التلقائي (ON DUPLICATE KEY)
    $sql = "INSERT INTO products (sku, name, price, img, category) 
            VALUES (?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE price = VALUES(price), last_sync = NOW()";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        $data['sku'],
        $data['name'],
        $data['price'],
        $data['img'],
        $data['category']
    ]);

    echo json_encode(["status" => "success"]);
}
