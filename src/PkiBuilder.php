<?php

namespace Iyzipay;

class PkiBuilder
{
    private $pkiString = "";

    public function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }

    public function append($key, $value)
    {
        if ($value !== null && $value !== "") {
            $this->pkiString .= $key . "=" . $value . ",";
        }
        return $this;
    }

    public function appendPrice($key, $price)
    {
        if ($price !== null && $price !== "") {
            $priceStr = (string)$price;
            if (strpos($priceStr, '.') === false) {
                $priceStr .= '.0';
            }
            $this->pkiString .= $key . "=" . $priceStr . ",";
        }
        return $this;
    }

    public function appendArray($key, array $array = null)
    {
        if ($array !== null && !empty($array)) {
            $appendedValue = "";
            foreach ($array as $value) {
                if ($value instanceof \Iyzipay\Request\PkiRequestNode) {
                    $appendedValue .= $value->toPKIRequestString() . ",";
                }
                elseif (is_array($value)) {
                // This handles generic array objects if needed (not standard for Iyzipay though)
                }
                else {
                    $appendedValue .= $value . ",";
                }
            }
            $appendedValue = rtrim($appendedValue, ',');
            $this->pkiString .= $key . "=[" . $appendedValue . "],";
        }
        return $this;
    }

    public function getRequestString()
    {
        $this->pkiString = rtrim($this->pkiString, ',');
        return "[" . $this->pkiString . "]";
    }
}
