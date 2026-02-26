
<?php
echo "Hello, World!";
// 1. الرابط الذي استخرجته من المتصفح
const mainCategory = [

    'New' => "https://ar.shein.com/bff-api/ccc/nav/right?_ver=1.1.8&_lang=ar&channelType=10&id=757041&leftCateName=New+In",

    "Women+Clothing" =>
    "https://ar.shein.com/bff-api/ccc/nav/right?_ver=1.1.8&_lang=ar&channelType=10&id=common:346016:shein:ar_ar:ios&leftCateName=Women+Clothing",


    'Curve' =>
    "https://ar.shein.com/bff-api/ccc/nav/right?_ver=1.1.8&_lang=ar&channelType=10&id=757053&leftCateName=Curve",


    "تخفيض+الأسعار" =>
    "https://ar.shein.com/bff-api/ccc/nav/right?_ver=1.1.8&_lang=ar&channelType=10&id=757048&leftCateName=%D8%AA%D8%AE%D9%81%D9%8A%D8%B6+%D8%A7%D9%84%D8%A3%D8%B3%D8%B9%D8%A7%D8%B1",


    'Kids' => "https://ar.shein.com/bff-api/ccc/nav/right?_ver=1.1.8&_lang=ar&channelType=10&id=common:350613:shein:ar_ar:ios&leftCateName=Kids",


    'Men' => "https://ar.shein.com/bff-api/ccc/nav/right?_ver=1.1.8&_lang=ar&channelType=10&id=common:350668:shein:ar_ar:ios&leftCateName=Men+Clothing",

];
$url = mainCategory['Kids']; // اختر الرابط الذي تريد اختباره

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
                    'link'     => "https://ar.shein.com" . ($item['categoryType']['webClickUrl'] ?? ''),
                    'cat_id'     => $item['categoryType']['hrefTarget'] ?? '',

                ];
            }
        }
    }
}

// عرض النتائج بشكل جميل
header('Content-Type: application/json; charset=utf-8');
echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
