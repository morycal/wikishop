<?php
$data = json_decode(file_get_contents("php://input"), true);

$amount = $data["amount"] * 10;
$merchant_id = "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX";

$callback = "http://localhost/shop/verify.php";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.zarinpal.com/pg/v4/payment/request.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
  "merchant_id"=>$merchant_id,
  "amount"=>$amount,
  "callback_url"=>$callback,
  "description"=>"Order payment"
]));

curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$result = curl_exec($ch);
curl_close($ch);

$result = json_decode($result, true);

if(isset($result["data"]["authority"])){
  echo json_encode([
    "url"=>"https://www.zarinpal.com/pg/StartPay/".$result["data"]["authority"]
  ]);
}else{
  echo json_encode(["error"=>"fail"]);
}
