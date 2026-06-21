<?php

$data = json_decode(file_get_contents("php://input"), true);

$amount = $data["amount"] * 10; // تبدیل به ریال
$callback = "http://localhost/shop/verify.php";

$merchant_id = "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX"; // 🔴 مرچنت کد زرین‌پال

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.zarinpal.com/pg/v4/payment/request.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "merchant_id" => $merchant_id,
    "amount" => $amount,
    "callback_url" => $callback,
    "description" => "خرید از فروشگاه",
]));

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

$result = curl_exec($ch);
curl_close($ch);

$result = json_decode($result, true);

if(isset($result["data"]["authority"])){
    $url = "https://www.zarinpal.com/pg/StartPay/" . $result["data"]["authority"];
    echo json_encode(["url"=>$url]);
} else {
    echo json_encode(["error"=>"failed"]);
}
