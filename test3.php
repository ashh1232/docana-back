<?php
// 1. الرابط الأساسي
$baseUrl = "https://ar.shein.combff-api/product/recommend/ccc_component_common?_ver=1.1.8&_lang=ar";

$allProducts = [];
$totalToFetch = 100; // قلل الرقم للتجربة أولاً (مثلاً صفحتين)
$perPage = 50;

// 2. إعداد الطلب (Headers ثابتة وقوية لتجنب 403)
$httpOptions = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36\r\n" .
            "Accept: application/json, text/plain, */*\r\n" .
            "Accept-Language: ar,en-US;q=0.9,en;q=0.8\r\n" .
            "Referer: https://ar.shein.com\r\n" .
            "Origin: https://ar.shein.com\r\n" .
            "Cache-Control: no-cache\r\n",
        "ignore_errors" => true // للسماح بقراءة محتوى الخطأ إذا حدث
    ],
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ]
];

$context = stream_context_create($httpOptions);

// 3. حلقة التكرار
for ($page = 1; $page <= ($totalToFetch / $perPage); $page++) {

    $currentUrl = $baseUrl . "&page=" . $page . "&limit=" . $perPage;

    // استخدام الـ context المعرف مسبقاً بالـ Headers القوية
    $response = @file_get_contents($currentUrl, false, $context);

    if ($response) {
        $data = json_decode($response, true);

        // تأكد من هيكلية الـ JSON (في بعض روابط الـ recommend تكون تحت 'info' مباشرة)
        $products = $data['info']['products'] ?? [];

        foreach ($products as $item) {
            $allProducts[] = [
                'id'    => $item['goods_id'],
                'name'  => $item['goods_name'],
                'price' => $item['salePrice']['amountWithSymbol'] ?? 'N/A',
                'img'   => "https:" . ($item['goods_img'] ?? '')
            ];
        }
    }

    // انتظر قليلاً لحماية الـ IP
    usleep(800000);
}

// 4. النتيجة
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'total_scraped' => count($allProducts),
    'data' => $allProducts
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
