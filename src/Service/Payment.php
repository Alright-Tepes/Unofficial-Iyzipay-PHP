<?php

namespace Iyzipay\Service;

use Iyzipay\Config;
use Iyzipay\HttpClient;
use Iyzipay\HashGenerator;
use Iyzipay\Request\PaymentRequest;

class Payment
{
    public static function create(PaymentRequest $request, Config $config)
    {
        $pkiString = $request->toPKIRequestString();
        $payload = $request->toJson();

        $headers = HashGenerator::generateHttpHeaders($config, $pkiString);
        $url = rtrim($config->getBaseUrl(), '/') . "/payment/auth";

        return HttpClient::post($url, $headers, $payload);
    }
}
