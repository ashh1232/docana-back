<?php
// هذا هو السطر المطلوب
ob_start(); // ابدأ تخزين المخرجات المؤقت
require_once __DIR__ . '/../vendor/autoload.php';

use kornrunner\Blurhash\Blurhash;

error_reporting(E_ALL);
ini_set('display_errors', 1);
// إعدادات الاتصال بقاعدة البيانات
include "../connect.php"; // تأكد أن هذا الملف يحتوي على اتصال $con
header('Content-Type: application/json'); // أخبر فلاتر أن الرد JSON
// 1. استقبال البيانات النصية
$name  = filterRequest("name");

// اسم المجلد الذي ستخزن فيه الصور
// $folder = realpath(__DIR__ . "/../../../img/catsImages"); // $folder = "/var/www/html/img/"; 
//  $folder = realpath(__DIR__ ."/var/www/html/img/catsImages"); 
$folder = "/var/www/html/img/catsImages";
$response = array();
// سيطبع لك كل ما وصل للسيرفر في Raw Response في Flutter
print_r($_POST);
print_r($_FILES);

// 2. معالجة رفع الملف (الصورة)
// ملاحظة: "files" هو الاسم الذي استخدمناه في Flutter داخل http.MultipartFile
if (empty($_FILES)) {
    echo json_encode(array(
        "status" => "failure",
        "message" => "No files received. Keys found in POST: " . implode(', ', array_keys($_POST))
    ));
    exit();
}
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
                $width = 32;
                $height = 32;
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
            $stmt = $con->prepare("INSERT INTO `categories` (`categories_name`,  `categories_image`, `categories_blurhash`) VALUES (?, ?, ?)");
            $stmt->execute(array($name,  $newImageName, $blurhash));

            if ($stmt->rowCount() > 0) {
                echo json_encode(array("status" => "success", "blurhash" => $blurhash));
            } else {
                echo json_encode(array("status" => "failure"));
            }
            ob_end_clean(); // امسح أي تحذيرات (Warnings) ظهرت بالأعلى
            echo json_encode($response);
            if (empty($response)) {
                echo json_encode(array("status" => "failure", "message" => "Reached end of script without output"));
            }
            if ($stmt->rowCount() > 0) {
                $res = array("status" => "success", "blurhash" => $blurhash);
            } else {
                $res = array("status" => "failure");
            }

            ob_clean(); // تمسح أي مخرجات سابقة مثل [] أو التحذيرات
            echo json_encode($res);
            exit(); // تضمن عدم طباعة أي شيء آخر بعد الـ JSON

        }
    } else {
        echo json_encode(array("status" => "failure", "message" => $error[0]));
    }
} else {
    echo json_encode(array("status" => "failure", "message" => "No image file received"));
}
if (empty($response)) {
    echo json_encode(array("status" => "failure", "message" => "Reached end of script without output"));
}
