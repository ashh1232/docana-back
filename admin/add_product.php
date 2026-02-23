<?php
require_once __DIR__ . '/../vendor/autoload.php';
include "../connect.php"; // Make sure this file exists with your DB connection

use Intervention\Image\ImageManagerStatic as Image; // استدعاء المكتبة
use kornrunner\Blurhash\Blurhash;

// ... (بقية الأكواد الخاصة بـ .env والاتصال بقاعدة البيانات) ...
// 1. استقبال البيانات النصية
$name  = filterRequest("name");
$price = filterRequest("price");
$vendor = filterRequest("vendor");
$catId = filterRequest("catId");
$note = filterRequest("note");

// اسم المجلد الذي ستخزن فيه الصور
$folder = realpath(__DIR__ . "/../../../img/productsImages"); // $folder = "/var/www/html/img/"; 
if (isset($_FILES['files'])) {
    $imageTmp  = $_FILES['files']['tmp_name'];
    $imageName = $_FILES['files']['name'];

    // اسم فريد للصورة وتحويل الامتداد لـ jpg لتوفير مساحة
    $newImageName = time() . "_" . uniqid() . ".jpg";
    $destination  = $folder . "/" . $newImageName;

    try {
        // --- [ 1. معالجة الصورة وتوفير المساحة ] ---
        $img = Image::make($imageTmp);

        // تصغير العرض لـ 800 بكسل مع الحفاظ على التناسب (Proportions)
        // ومنع تكبير الصور الصغيرة (upsize)
        $img->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // حفظ الصورة بجودة 70% (ضغط عالي مع جودة ممتازة)
        $img->save($destination, 70);

        // --- [ 2. توليد BlurHash من الصورة المعالجة ] ---
        // نستخدم نسخة صغيرة جداً للهاش (32 بكسل) لسرعة الأداء
        $smallImg = clone $img;
        $smallImg->resize(32, 32);

        $pixels = [];
        for ($y = 0; $y < 32; $y++) {
            $row = [];
            for ($x = 0; $x < 32; $x++) {
                $color = $smallImg->pickColor($x, $y); // استخراج اللون بسهولة
                $row[] = [$color[0], $color[1], $color[2]];
            }
            $pixels[] = $row;
        }
        $blurhash = Blurhash::encode($pixels, 4, 3);

        // --- [ 3. الحفظ في قاعدة البيانات ] ---
        $stmt = $con->prepare("INSERT INTO `products` (`vendor_id`, `product_name`, `product_price`, `product_image`, `product_blurhash`, `product_cat`, `product_desc`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$vendor, $name, $price, $newImageName, $blurhash, $catId, $note]);

        echo json_encode(["status" => "success", "blurhash" => $blurhash]);
    } catch (Exception $e) {
        echo json_encode(["status" => "failure", "message" => $e->getMessage()]);
    }
}
