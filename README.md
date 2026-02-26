# Unofficial Iyzipay PHP Payment Integration 

This library allows you to integrate the Iyzico (Iyzipay) API using pure PHP and cURL without any external dependencies (like the official SDK). it is lightweight, flexible, and easy to integrate into any PHP project.

## Features

- **Zero Dependencies**: Requires no external libraries or packages.
- **Secure**: Includes the IYZWS (PKI String) signature algorithm internally.
- **OOP Architecture**: Object-oriented, readable, and extensible code structure.
- **Fast**: Minimal overhead due to its lightweight design.
- **Flexible**: Works with any framework like Laravel, Symfony, CodeIgniter, or with pure PHP.

## Requirements

- PHP 7.4 or higher
- PHP cURL extension
- PHP JSON extension

## Installation

Simply include the class files in your project:

```php
require_once 'src/Config.php';
require_once 'src/PkiBuilder.php';
require_once 'src/HashGenerator.php';
require_once 'src/HttpClient.php';
require_once 'src/Request/PaymentRequest.php';
require_once 'src/Service/Payment.php';
require_once 'src/Model/Buyer.php';
require_once 'src/Model/Address.php';
require_once 'src/Model/BasketItem.php';
```

## Usage Example

```php
use Iyzipay\Config;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Request\PaymentRequest;
use Iyzipay\Service\Payment;

// Configuration
$config = new Config();
$config->setApiKey("sandbox-api-key");
$config->setSecretKey("sandbox-secret-key");
$config->setBaseUrl("https://sandbox-api.iyzipay.com");

// Payment Request
$request = new PaymentRequest();
$request->price = "1.00";
$request->paidPrice = "1.20";
$request->currency = "TRY";
$request->basketId = "B67832";

// Card Information
$request->paymentCard = [
    "cardHolderName" => "John Doe",
    "cardNumber" => "5555444433332222",
    "expireMonth" => "12",
    "expireYear" => "2025",
    "cvc" => "123"
];

// Buyer Information
$request->buyer = new Buyer([
    "id" => "BY789",
    "name" => "John",
    "surname" => "Doe",
    "email" => "email@email.com",
    "identityNumber" => "74300864791",
    "city" => "Istanbul",
    "country" => "Turkey"
]);

// Address Information
$address = new Address([
    "contactName" => "John Doe",
    "city" => "Istanbul",
    "country" => "Turkey",
    "address" => "Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1"
]);
$request->billingAddress = $address;
$request->shippingAddress = $address;

// Basket Items
$request->basketItems = [
    new BasketItem([
        "id" => "BI101",
        "name" => "Binocular",
        "category1" => "Collectibles",
        "itemType" => BasketItem::B1_PHYSICAL,
        "price" => "1.00"
    ])
];

// Create Payment
$response = Payment::create($request, $config);
$result = json_decode($response, true);

if ($result['status'] === 'success') {
    echo "Payment Successful!";
} else {
    echo "Error: " . $result['errorMessage'];
}
```

## Important Notes

- The PKI String generation algorithm is fully compatible with Iyzico standards.
- The `price` and `paidPrice` fields must be sent as string types (`"1.00"`) and in decimal format.
- We recommend using an Iyzico Sandbox account for testing.

## License

Licensed under the MIT License.
