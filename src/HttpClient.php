<?php

namespace Iyzipay;

class HttpClient
{
    public static function post($url, $httpHeaders, $content)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);

        // Temporarily ignore SSL for local testing (can be configured in Config later)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($err) {
            return json_encode([
                "status" => "failure",
                "errorCode" => "CURL_ERROR",
                "errorMessage" => "cURL Error #:" . $err
            ]);
        }

        return $response;
    }

    public static function get($url, $httpHeaders)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
            return json_encode([
                "status" => "failure",
                "errorCode" => "CURL_ERROR",
                "errorMessage" => "cURL Error #:" . $err
            ]);
        }

        return $response;
    }
}
