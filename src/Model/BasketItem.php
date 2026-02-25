<?php

namespace Iyzipay\Model;

class BasketItem
{
    const B1_PHYSICAL = "PHYSICAL_ITEM";
    const B1_VIRTUAL = "VIRTUAL_ITEM";

    private $id;
    private $name;
    private $category1;
    private $category2;
    private $itemType;
    private $price;

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
                $result[$key] = (string)$value; // Ensure numbers are stringified for API
            }
        }
        return $result;
    }
}
