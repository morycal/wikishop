<?php
include "db.php";

$merchant_id = "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX";

$status = $_GET["Status"];
$authority = $_GET["Authority"];

if($status != "OK"){
  die("پرداخت ناموفق");
}

$amount = 10000 * 10;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.zarinpal.com/pg/v4/payment/verify.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
  "merchant_id"=>$merchant_id,
  "authority"=>$authority,
  "amount"=>$amount
]));

curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$result = curl_exec($ch);
curl_close($ch);

$result = json_decode($result, true);

if($result["data"]["code"] == 100){

$ref = $result["data"]["ref_id"];

$conn->query("INSERT INTO orders(amount,status,ref_id)
VALUES($amount,'paid','$ref')");

echo "پرداخت موفق ✔ <br> کد پیگیری: ".$ref;

}else{
echo "پرداخت ناموفق ❌";
}
