<?php
header('Content-Type: application/json; charset=utf-8');

$API_KEY = 'CWA-FAKE'; 

$locationCode = 'F-D0047-063'; // 台北市
$url = "https://opendata.cwa.gov.tw/api/v1/rest/datastore/{$locationCode}?Authorization={$API_KEY}&format=JSON&elementName=Wx,MaxT";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0'
]);

$response = curl_exec($ch);

// 直接輸出（如果還是有問題會看到原始資料）
$data = json_decode($response, true);

$forecasts = [];

if (isset($data['success']) && $data['success'] === 'true') {
    $locations = $data['records']['locations'][0]['location'] ?? [];
    
    foreach ($locations as $location) {
        if ($location['locationName'] !== '臺北市') continue; // 確保是台北市
        
        $wxElement = null;
        $maxTElement = null;
        
        foreach ($location['weatherElement'] as $el) {
            if ($el['elementName'] === 'Wx') $wxElement = $el;
            if ($el['elementName'] === 'MaxT') $maxTElement = $el;
        }
        
        if (!$wxElement) continue;
        
        foreach ($wxElement['time'] as $i => $time) {
            $date = substr($time['startTime'], 0, 10);
            if (isset($forecasts[$date])) continue; // 每天只取第一筆
            
            $wx = $time['elementValue'][0]['value'] ?? '未知';
            $maxT = '??';
            
            if ($maxTElement && isset($maxTElement['time'][$i])) {
                $maxT = $maxTElement['time'][$i]['elementValue'][0]['value'] ?? '??';
            }
            
            $icon = match(true) {
                str_contains($wx, '晴') => '晴天',
                str_contains($wx, '多雲') => '多雲',
                str_contains($wx, '陰') => '陰天',
                str_contains($wx, '雨') => '雨天',
                str_contains($wx, '雷') => '雷雨',
                default => '雲',
            };
            
            $forecasts[$date] = [
                'date' => $date,
                'icon' => $icon,
                'maxT' => $maxT
            ];
        }
        break; // 找到台北市就結束
    }
}

echo json_encode(array_values($forecasts));
?>