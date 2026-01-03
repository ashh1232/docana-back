<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);
// إعدادات الاتصال بقاعدة البيانات
include "../connect.php"; // تأكد أن هذا الملف يحتوي على اتصال $con

// 1. استقبال البيانات النصية
$name  = filterRequest("name");
$price = filterRequest("price");

// اسم المجلد الذي ستخزن فيه الصور
// $folder = "../../../img"; 
$folder = "/var/www/html/img/"; 
//  $target_dir = "/var/www/html/img/"; 

// 2. معالجة رفع الملف (الصورة)
// ملاحظة: "files" هو الاسم الذي استخدمناه في Flutter داخل http.MultipartFile
if (isset($_FILES['files'])) {
    
    $imageName = $_FILES['files']['name'];
    $imageTmp  = $_FILES['files']['tmp_name'];
    $imageSize = $_FILES['files']['size'];
    
    // استخراج الامتداد والتأكد من نوع الملف
    $allowExt  = array("jpg", "png", "gif", "jpeg");
    $strToArray = explode(".", $imageName);
    $ext        = end($strToArray);
    $ext        = strtolower($ext);

    if (!empty($imageName) && !in_array($ext, $allowExt)) {
        $error[] = "الامتداد غير مسموح به";
    }

    // إذا لم يكن هناك أخطاء، قم برفع الصورة وحفظ البيانات
    if (empty($error)) {
        
        // توليد اسم فريد للصورة لتجنب التكرار
        $newImageName = rand(1000, 10000) . "_" . $imageName;
        
        // التأكد من وجود المجلد
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        // نقل الصورة للمجلد
        move_uploaded_file($imageTmp, $folder . "/" . $newImageName);

        // 3. إدخال البيانات في قاعدة البيانات
        $stmt = $con->prepare("INSERT INTO `products` (`product_name`, `product_price`, `product_image`) VALUES (?, ?, ?)");
        $stmt->execute(array($name, $price, $newImageName));

        $count = $stmt->rowCount();

        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure", "message" => "Database insert failed"));
        }
    } else {
        echo json_encode(array("status" => "failure", "message" => $error[0]));
    }
} else {
    echo json_encode(array("status" => "failure", "message" => "No image file received"));
}

// فنكشن بسيطة لتنظيف المدخلات (تأكد من وجودها في ملف connect.php أو أضفها هنا)
// function filterRequest($requestname) {
//     return  htmlspecialchars(strip_tags($_POST[$requestname]));
// }
?>
