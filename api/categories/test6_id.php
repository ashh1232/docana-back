<?php
include_once "../connect.php";

// 1. استقبال المعرف والتأكد من أنه ليس فارغاً
$cat_id = filterRequest("cat_id");

if (!empty($cat_id)) {

    // 2. إضافة علامات تنصيص حول المتغير لضمان أمان الـ SQL
    // افترضنا أن اسم العمود في جدول المنتجات هو 'cat_id' أو 'cato_id'
    getAllData('products', "cat_id = '$cat_id'");
} else {
    // 3. رد في حال عدم إرسال المعرف
    echo json_encode([
        "status" => "error",
        "message" => "Missing category ID"
    ]);
}
