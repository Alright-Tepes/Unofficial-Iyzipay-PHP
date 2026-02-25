<?php

namespace Iyzipay\Model;

class Buyer
{
    private $id;
    private $name;
    private $surname;
    private $gsmNumber;
    private $email;
    private $identityNumber;
    private $lastLoginDate;
    private $registrationDate;
    private $registrationAddress;
    private $ip;
    private $city;
    private $country;
    private $zipCode;

    // Getters and setters omitted for brevity, adding a bulk setter for ease of use
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray()
    {
        $result = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($value !== null) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
