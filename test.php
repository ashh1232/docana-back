
<?php
echo "Hello, World!";
// 1. الرابط الذي استخرجته من المتصفح
$url = "https://ar.shein.com/bff-api/ccc/nav/right?_ver=1.1.8&_lang=ar&channelType=10&id=757041&leftCateName=New+In";

// 2. إعداد الطلب (Headers مهمة جداً ليوهم الموقع أنك متصفح)
$options = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36\r\n" .
            "Accept: application/json\r\n" .
            "Referer: https://ar.shein.com\r\n"
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    die("خطأ في الاتصال بالرابط");
}

// 3. تحويل النص الجييسون إلى مصفوفة PHP
// $json_data = "https://ar.shein.com/bff-api/ccc/nav/right?_ver=1.1.8&_lang=ar&channelType=10&id=757041&leftCateName=New+In";
// افترضنا أن $json_data هو المتغير الذي يحتوي على النص الذي أرسلته
$data = json_decode($response, true);

$results = [];

if (isset($data['info']['contents'])) {
    foreach ($data['info']['contents'] as $content) {
        // استخراج القسم الرئيسي
        $main_name = $content['props']['metaData']['categoryLanguage'] ?? '';

        // استخراج العناصر الفرعية (Items)
        if (isset($content['child'][0]['props']['items'])) {
            foreach ($content['child'][0]['props']['items'] as $item) {
                $results[] = [
                    'category' => $main_name,
                    'name'     => $item['categoryLanguage'],
                    'link'     => "https://ar.shein.com" . ($item['categoryType']['webClickUrl'] ?? '')
                ];
            }
        }
    }
}

// عرض النتائج بشكل جميل
header('Content-Type: application/json; charset=utf-8');
echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
