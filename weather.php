<?php
// ===== 純手工讀 .env（完全不用 composer）=====
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // 跳過註解
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

header('Content-Type: application/json; charset=utf-8');

$API_KEY = $_ENV['CWA_API_KEY'];
$url = "https://opendata.cwa.gov.tw/api/v1/rest/datastore/F-D0047-063?Authorization={$API_KEY}";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0'
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!$data || $data['success'] !== 'true') {
    echo json_encode(['error' => 'API 失敗']);
    exit;
}

$location = $data['records']['Locations'][0]['Location'][0] ?? null;
if (!$location) {
    echo json_encode([]);
    exit;
}

// 找出兩個關鍵元素
$descTimes = [];  // 天氣預報綜合描述
$maxTTimes = [];  // 最高溫度

foreach ($location['WeatherElement'] as $el) {
    if ($el['ElementName'] === '天氣預報綜合描述') $descTimes = $el['Time'];
    if ($el['ElementName'] === '最高溫度')         $maxTTimes = $el['Time'];
}

$forecasts = [];

foreach ($descTimes as $i => $time) {
    $date = substr($time['StartTime'], 0, 10);
    if (isset($forecasts[$date])) continue; // 每天只取一筆

    // 1. 取天氣描述（從 WeatherDescription）
    $desc = $time['ElementValue'][0]['WeatherDescription'] ?? '未知天氣';
    
    // 2. 取最高溫（從 MaxTemperature）
    $maxT = '??';
    if (isset($maxTTimes[$i]['ElementValue'][0]['MaxTemperature'])) {
        $maxT = $maxTTimes[$i]['ElementValue'][0]['MaxTemperature'];
    }

    // 3. 從長描述中判斷 icon（超穩定方式）
    $icon = '雲';
    if (strpos($desc, '晴') !== false && strpos($desc, '雲') === false && strpos($desc, '雨') === false) {
        $icon = '晴天';
    } elseif (strpos($desc, '多雲') !== false || strpos($desc, '晴時多雲') !== false || strpos($desc, '多雲時晴') !== false) {
        $icon = '多雲';
    } elseif (strpos($desc, '陰') !== false) {
        $icon = '陰天';
    } elseif (strpos($desc, '雨') !== false || strpos($desc, '陣雨') !== false) {
        $icon = '雨天';
    } elseif (strpos($desc, '雷') !== false) {
        $icon = '雷雨';
    }

    $forecasts[$date] = [
        'date' => $date,
        'weather' => $desc,
        'icon' => $icon,
        'maxT' => $maxT . '°C'
    ];
}

echo json_encode(array_values($forecasts), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>