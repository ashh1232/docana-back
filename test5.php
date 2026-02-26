<?php
// 1. المفاتيح الخاصة بك
$apiKey = "AIzaSyD3orM7bpanji6Vv58_5UCu1fALWASpE0U";
$cx     = "546cd4f57162a4eb3";
$query  = "فساتين شي إن";

$params = [
    'key' => $apiKey,
    'cx'  => $cx,
    'q'   => $query,
    'num' => 10
];
// 2. تصحيح الرابط (المسار وعلامة الاستفهام والـ key ضروريين جداً)
$url = "https://www.googleapis.com/customsearch/v1?" . http_build_query($params);;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // لتخطي أخطاء الـ SSL في XAMPP
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

header('Content-Type: application/json; charset=utf-8');

if ($response === false) {
    echo json_encode([
        "status" => "error",
        "message" => "CURL Error: " . $curlError
    ]);
} else {
    // إذا نجح الاتصال، سيعرض جوجل الـ JSON الكامل هنا
    echo $response;
}
