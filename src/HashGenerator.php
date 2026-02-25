<?php

namespace Iyzipay;

class HashGenerator
{
    /**
     * Iyzico hash creation algorithm for V1 (IYZWS)
     * 
     * @param Config $config
     * @param string $randomString
     * @param string $pkiString
     * @return string Base64 encoded SHA-1 hash
     */
    public static function generateHash(Config $config, $randomString, $pkiString)
    {
        $hashStr = $config->getApiKey() . $randomString . $config->getSecretKey() . $pkiString;
        return base64_encode(sha1($hashStr, true));
    }

    /**
     * Generates required HTTP headers for Iyzico API
     * 
     * @param Config $config
     * @param string $pkiString
     * @return array
     */
    public static function generateHttpHeaders(Config $config, $pkiString = '')
    {
        $randomString = uniqid();

        $authorization = '';
        if ($config->getApiKey() && $config->getSecretKey()) {
            $hash = self::generateHash($config, $randomString, $pkiString);
            $authorization = 'IYZWS ' . $config->getApiKey() . ':' . $hash;
        }

        return [
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $authorization,
            'x-iyzi-rnd: ' . $randomString,
            'x-iyzi-client-version: iyzipay-php-custom'
        ];
    }
}
