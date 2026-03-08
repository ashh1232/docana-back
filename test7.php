
<?php
// echo "Hello, World!";
// 1. الرابط الذي استخرجته من المتصفح
const urll = "https://m.shein.com/bff-api/ccc/home_page?_ver=1.1.8&_lang=en&channel_id=25&country_id=165&position=2&tab_id=1&tab_name=women";
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
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
if ($page === null || $page === false || $page < 1) {
    $page = 1;
}
$limit = 20;
$offset = ($page - 1) * $limit;
$totalRecords = 1000;
$totalPages = ceil($totalRecords / $limit);
$url = trim("https://m.shein.com/bff-api/ccc/home_page?_ver=1.1.8&_lang=ar&channel_id=$page&country_id=165&position=2&tab_id=1&tab_name=men"); // اختر الرابط الذي تريد اختباره

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
if (json_last_error() !== JSON_ERROR_NONE) {
    die("خطأ في تحليل JSON: " . json_last_error_msg());
}


$category = [];
$results = [];
header('Content-Type: application/json; charset=utf-8');
$contents = $data['info']['content'] ?? [];
if (isset($data['info']['content'])) {
    $category[] = ['as' => $contents];
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

            // $category[] = [
            //     'id'    => $cat_id,
            //     'title' => $item['categoryLanguage'] ?? 'بدون عنوان',
            //     'image' => $cleanImage,
            //     'link'  => "https://ar.shein.com" . ($item['categoryType']['webClickUrl'] ?? '')
            // ];
        }
    }
    // echo "Hello, World!";
    foreach ($data['info']['content'] as $content) {
        if (isset($content['content'])) {
            foreach ($content['content'] as $itema) {
                foreach ($itema['props']['metaData']['productsV2'] as $itemaaa) {
                    $detailImages = []; // تفريغ المصفوفة لكل منتج جديد
                    if (isset($itemaaa['detail_image'])) {
                        foreach ($itemaaa['detail_image'] as $detImga) {
                            // استخدام الرابط المباشر للصورة إذا وجد
                            $detailImages[] = "https:" . ($detImga ?? '');
                        }
                    }
                    $results[] = [
                        'product_id' => $itemaaa['goods_id'] ?? '',
                        'product_name'     => $itemaaa['goods_name'] ?? '',
                        'product_price'     => $itemaaa["salePrice"]['amount'] ?? '',
                        'link'     => "https://ar.shein.com" . ($itemaaa['goods_url_name'] ?? '') . "-p-" . ($itemaaa['goods_id'] ?? '') . ".html",
                        'product_cat'   => $itemaaa['cat_id'] ?? '',
                        'product_image'    => "https:" . ($itemaaa['goods_img'] ?? ''),
                        'detail_image' => $detailImages,
                        'product_desc'   => $itemaaa['goods_name'] ?? '',

                    ];
                }
            }
        }

        if (isset($content['props']['items'])) {

            foreach ($content['props']['items'] as $item) {

                if (isset($item['productsV2'])) {
                    foreach ($item['productsV2'] as $product) {
                        $detailImages = [];
                        if (isset($product['detail_image'])) {
                            foreach ($product['detail_image'] as $detImg) {
                                // استخدام الرابط المباشر للصورة إذا وجد
                                $detailImages[] = "https:" . ($detImg ?? '');
                            }
                        }

                        $results[] = [
                            'product_id' => $product['goods_id'] ?? '',
                            'product_name'     => $product['goods_name'] ?? '',
                            'product_price'     => $product["salePrice"]['amount'] ?? '',
                            'link'     => "https://ar.shein.com" . ($product['goods_url_name'] ?? '') . "-p-" . ($product['goods_id'] ?? '') . ".html",
                            'product_cat'   => $product['cat_id'] ?? '',
                            'product_image'    => "https:" . ($product['goods_img'] ?? ''),
                            'detail_image' => $detailImages,
                        ];
                    }
                }
            }
        }
    }
}

if (isset($data['info']['contents'])) {
    echo "Hello, World!";

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
echo json_encode([
    "status" => "success",
    "data" => $results,
    // "category" => $category,
    "metadata" => [
        "current_page" => $page,
        "per_page" => $limit,
        "total_records" => (int)$totalRecords,
        "total_pages" => (int)$totalPages,
        "has_more" => $page < $totalPages
    ]
], JSON_UNESCAPED_UNICODE);
