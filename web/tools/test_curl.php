<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Testing curl...\n";
$url = 'https://api.api168168.com/pks/getLotteryPksInfo.do?lotCode=10035';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$data = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch) . "\n";
} else {
    echo "Curl success. Data length: " . strlen($data) . "\n";
    // echo "Data: " . substr($data, 0, 100) . "...\n";
}
curl_close($ch);
?>
