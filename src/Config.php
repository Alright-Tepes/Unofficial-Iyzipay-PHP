<?php

namespace Iyzipay;

class Config
{
    private $apiKey;
    private $secretKey;
    private $baseUrl;

    public function __construct()
    {
        // Default to sandbox URL
        $this->baseUrl = "https://sandbox-api.iyzipay.com";
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }
}
