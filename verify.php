<?php

$merchant_id = "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX";

$authority = $_GET["Authority"];
$status = $_GET["Status"];

if($status != "OK"){
    die("پرداخت ناموفق بود ❌");
}

$amount = 10000 * 10;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.zarinpal.com/pg/v4/payment/verify.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "merchant_id" => $merchant_id,
    "authority" => $authority,
    "amount" => $amount,
]));

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

$result = curl_exec($ch);
curl_close($ch);

$result = json_decode($result, true);

if(isset($result["data"]["code"]) && $result["data"]["code"] == 100){

    echo "پرداخت موفق بود ✔<br>";
    echo "کد پیگیری: " . $result["data"]["ref_id"];

} else {
    echo "پرداخت تایید نشد ❌";
}
