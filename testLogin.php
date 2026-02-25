<?php
$url = "https://ar.shein.com/bff-api/ccc/nav/right?_ver=1.1.8&_lang=ar&channelType=10&id=757041&leftCateName=New+In";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
$html = curl_exec($ch);
curl_close($ch);
// echo $html;
$dom = new DOMDocument();
@$dom->loadHTML($html);
$xpath = new DOMXPath($dom);
$categories = $xpath->query('//div[contains(@class, "bs-cate_one-item")]');
$data = [];
foreach ($categories as $cat) {
    $link = $xpath->query('.//a', $cat)->item(0);
    if ($link) {
        $data[] = [
            'name' => trim($link->nodeValue),
            'url' => $link->getAttribute('href')
        ];
    }
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
// echo "Category: " . trim($name) . " - Link: " . trim($link) . "\n";