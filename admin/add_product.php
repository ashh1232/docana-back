<?php
// هذا هو السطر المطلوب
require_once __DIR__ . '/../vendor/autoload.php'; 

use kornrunner\Blurhash\Blurhash;

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// إعدادات الاتصال بقاعدة البيانات
include "../connect.php"; // تأكد أن هذا الملف يحتوي على اتصال $con

// 1. استقبال البيانات النصية
$name  = filterRequest("name");
$price = filterRequest("price");

// اسم المجلد الذي ستخزن فيه الصور
// $folder = realpath(__DIR__ . "/../../../img/productsImages"); // $folder = "/var/www/html/img/"; 
//  $folder = "/var/www/html/img/productsImages/"; 
    $folder = realpath(__DIR__ . "/../../../img/productsImages/"); // $folder = "/var/www/html/img/"; 

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
 $destination = $folder . "/" . $newImageName;

    if (move_uploaded_file($imageTmp, $destination)) {
        
        // --- [ بداية عملية BlurHash ] ---
        try {
            // 1. إنشاء نسخة مصغرة جداً للمعالجة (أداء أسرع)
            $width = 32; $height = 32;
            $img = imagecreatefromstring(file_get_contents($destination));
            $resizedImg = imagecreatetruecolor($width, $height);
            imagecopyresampled($resizedImg, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));

            // 2. استخراج البكسلات
            $pixels = [];
            for ($y = 0; $y < $height; $y++) {
                $row = [];
                for ($x = 0; $x < $width; $x++) {
                    $index = imagecolorat($resizedImg, $x, $y);
                    $colors = imagecolorsforindex($resizedImg, $index);
                    $row[] = [$colors['red'], $colors['green'], $colors['blue']];
                }
                $pixels[] = $row;
            }

            // 3. توليد الهاش (Components 4x4 هي الأنسب)
            $blurhash = Blurhash::encode($pixels, 4, 4);
            imagedestroy($img);
            imagedestroy($resizedImg);
        } catch (Exception $e) {
            $blurhash = ""; // في حال الفشل
        }
        // --- [ نهاية عملية BlurHash ] ---

        // 3. حفظ البيانات (أضف عمود product_blurhash في قاعدة البيانات)
        $stmt = $con->prepare("INSERT INTO `products` (`product_name`, `product_price`, `product_image`, `product_blurhash`) VALUES (?, ?, ?, ?)");
        $stmt->execute(array($name, $price, $newImageName, $blurhash));

        if ($stmt->rowCount() > 0) {
            echo json_encode(array("status" => "success", "blurhash" => $blurhash));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
        // نقل الصورة للمجلد
        // move_uploaded_file($imageTmp, $folder . "/" . $newImageName);

        // // 3. إدخال البيانات في قاعدة البيانات
        // $stmt = $con->prepare("INSERT INTO `products` (`product_name`, `product_price`, `product_image`) VALUES (?, ?, ?)");
        // $stmt->execute(array($name, $price, $newImageName));

        // $count = $stmt->rowCount();

        // if ($count > 0) {
        //     echo json_encode(array("status" => "success"));
        // } else {
        //     echo json_encode(array("status" => "failure", "message" => "Database insert failed"));
        // }
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
