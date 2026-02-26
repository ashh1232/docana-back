<?php
// 1. يجب تعريف الرابط هنا (تأكد من هذا السطر)
$url = "https://ar.shein.com/bff-api/ccc/nav/right?_ver=1.1.8&_lang=ar&channelType=10&id=757041&leftCateName=New+In";

// 2. إعداد الطلب
$options = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36\r\n" .
            "Accept: application/json\r\n" .
            "Referer: https://ar.shein.com\r\n"
    ],
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ]
];

$context = stream_context_create($options);
$response = @file_get_contents($url, false, $context);

if ($response === FALSE) {
    die(json_encode(["error" => "خطأ في الاتصال بالرابط - تأكد من الـ URL"]));
}

$data = json_decode($response, true);
$categories = [];

// 3. الدخول للهيكلية الصحيحة (تأكد من وجود info و contents)
$contents = $data['info']['contents'] ?? [];

foreach ($contents as $content) {
    // الوصول للمصفوفة المتداخلة items
    $items = $content['child'][0]['props']['items'] ?? ($content['props']['items'] ?? []);

    foreach ($items as $item) {
        $clickUrl = $item['categoryType']['clickUrl'] ?? '';
        $cat_id = 'unknown';

        if (!empty($clickUrl)) {
            $queryString = parse_url($clickUrl, PHP_URL_QUERY);
            parse_str($queryString, $queryParams);
            if (isset($queryParams['data'])) {
                $innerData = json_decode($queryParams['data'], true);
                $cat_id = $innerData['cat_id'] ?? ($innerData['sub_cat_id'] ?? 'unknown');
            }
        }
        $rawImage = $item['cover']['src'] ?? '';
        $cleanImage = !empty($rawImage) ? "https:" . str_replace(['https:', 'http:'], '', $rawImage) : null;

        $categories[] = [
            'id'    => $cat_id,
            'title' => $item['categoryLanguage'] ?? 'بدون عنوان',
            'image' => $cleanImage,
            'link'  => "https://ar.shein.com" . ($item['categoryType']['webClickUrl'] ?? '')
        ];
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    "status" => "success",
    "data" => $categories
], JSON_UNESCAPED_UNICODE);
