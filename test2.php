<?php
$url = "https://ar.shein.com/bff-api/product/recommend/ccc_component_common?_ver=1.1.8&_lang=ar";

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
// افترضنا أن $response هو النص الذي حصلت عليه من الـ API
$data = json_decode($response, true);

$products_list = [];

if (isset($data['info']['products'])) {
    foreach ($data['info']['products'] as $item) {
        $products_list[] = [
            'id'            => $item['goods_id'],
            'name'          => $item['goods_name'],
            'category'      => $item['cate_name'],
            'original_price' => $item['retailPrice']['amountWithSymbol'], // السعر قبل الخصم
            'sale_price'    => $item['salePrice']['amountWithSymbol'],   // السعر الحالي
            'discount_text' => $item['unit_discount'] . "%",             // نسبة الخصم
            'main_image'    => "https:" . $item['goods_img'],            // الصورة الأساسية
            'url'           => "https://ar.shein.com" . $item['goods_url_name'] . "-p-" . $item['goods_id'] . ".html",
            'is_sold_out'   => $item['soldOutStatus'] ? 'نفذت الكمية' : 'متوفر'
        ];
    }
}

// عرض النتائج
header('Content-Type: application/json; charset=utf-8');
echo json_encode($products_list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
