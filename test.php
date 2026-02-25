<?php

require_once __DIR__ . '/src/Config.php';
require_once __DIR__ . '/src/PkiBuilder.php';
require_once __DIR__ . '/src/HashGenerator.php';
require_once __DIR__ . '/src/HttpClient.php';
require_once __DIR__ . '/src/Request/PaymentRequest.php';
require_once __DIR__ . '/src/Service/Payment.php';
require_once __DIR__ . '/src/Model/Buyer.php';
require_once __DIR__ . '/src/Model/Address.php';
require_once __DIR__ . '/src/Model/BasketItem.php';

use Iyzipay\Config;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Request\PaymentRequest;
use Iyzipay\Service\Payment;

// iyzico sandbox default keys for testing (Replace with yours)
$config = new Config();
$config->setApiKey("sandbox-api-key");
$config->setSecretKey("sandbox-secret-key");
$config->setBaseUrl("https://sandbox-api.iyzipay.com");

$request = new PaymentRequest();
$request->locale = "tr";
$request->conversationId = "123456789";
$request->price = "1.00";
$request->paidPrice = "1.20";
$request->currency = "TRY";
$request->installment = 1;
$request->basketId = "B67832";
$request->paymentChannel = "WEB";
$request->paymentGroup = "PRODUCT";

$request->paymentCard = [
    "cardHolderName" => "John Doe",
    "cardNumber" => "5555444433332222",
    "expireMonth" => "12",
    "expireYear" => "2025",
    "cvc" => "123",
    "registerCard" => "0"
];

$buyer = new Buyer([
    "id" => "BY789",
    "name" => "John",
    "surname" => "Doe",
    "gsmNumber" => "+905320000000",
    "email" => "email@email.com",
    "identityNumber" => "74300864791",
    "lastLoginDate" => "2025-01-08 15:12:09",
    "registrationDate" => "2025-01-08 15:12:09",
    "registrationAddress" => "Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1",
    "ip" => "85.34.78.112",
    "city" => "Istanbul",
    "country" => "Turkey",
    "zipCode" => "34732"
]);
$request->buyer = $buyer;

$address = new Address([
    "contactName" => "Jane Doe",
    "city" => "Istanbul",
    "country" => "Turkey",
    "address" => "Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1",
    "zipCode" => "34732"
]);
$request->billingAddress = $address;
$request->shippingAddress = $address;

$item = new BasketItem([
    "id" => "BI101",
    "name" => "Binocular",
    "category1" => "Collectibles",
    "category2" => "Accessories",
    "itemType" => BasketItem::B1_PHYSICAL,
    "price" => "1.00"
]);
$request->basketItems = [$item];

echo "--- GONDERILEN JSON ---\n";
echo $request->toJson() . "\n\n";

echo "--- PKI STRING (DEBUG) ---\n";
echo $request->toPKIRequestString() . "\n\n";

echo "--- IYZIPAY RESPONSE ---\n";
$response = Payment::create($request, $config);
echo $response . "\n";
