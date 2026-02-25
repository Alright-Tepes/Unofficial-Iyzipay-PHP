<?php

namespace Iyzipay\Request;

use Iyzipay\PkiBuilder;

class PaymentRequest
{
    public $locale = "tr";
    public $conversationId;
    public $price;
    public $paidPrice;
    public $installment = 1;
    public $paymentChannel = "WEB";
    public $basketId;
    public $paymentGroup = "PRODUCT";
    public $paymentCard;
    public $buyer;
    public $shippingAddress;
    public $billingAddress;
    public $basketItems;
    public $currency = "TRY";

    public function toPKIRequestString()
    {
        $builder = PkiBuilder::create()
            ->append("locale", $this->locale)
            ->append("conversationId", $this->conversationId)
            ->appendPrice("price", $this->price)
            ->appendPrice("paidPrice", $this->paidPrice)
            ->append("installment", $this->installment)
            ->append("paymentChannel", $this->paymentChannel)
            ->append("basketId", $this->basketId)
            ->append("paymentGroup", $this->paymentGroup)
            ->append("paymentCard", $this->getPaymentCardPki())
            ->append("buyer", $this->getBuyerPki())
            ->append("shippingAddress", $this->getAddressPki($this->shippingAddress))
            ->append("billingAddress", $this->getAddressPki($this->billingAddress))
            ->appendArray("basketItems", $this->getBasketItemsPki())
            ->append("currency", $this->currency);

        return $builder->getRequestString();
    }

    private function getPaymentCardPki()
    {
        if (!$this->paymentCard)
            return null;
        $b = PkiBuilder::create();
        foreach (['cardHolderName', 'cardNumber', 'expireYear', 'expireMonth', 'cvc', 'registerCard'] as $key) {
            if (isset($this->paymentCard[$key])) {
                $b->append($key, $this->paymentCard[$key]);
            }
        }
        return $b->getRequestString();
    }

    private function getBuyerPki()
    {
        if (!$this->buyer)
            return null;
        $b = PkiBuilder::create();
        foreach ($this->buyer->toArray() as $k => $v) {
            $b->append($k, $v);
        }
        return $b->getRequestString();
    }

    private function getAddressPki($address)
    {
        if (!$address)
            return null;
        $b = PkiBuilder::create();
        foreach ($address->toArray() as $k => $v) {
            $b->append($k, $v);
        }
        return $b->getRequestString();
    }

    private function getBasketItemsPki()
    {
        if (!$this->basketItems)
            return null;
        $items = [];
        foreach ($this->basketItems as $item) {
            $b = PkiBuilder::create();
            foreach ($item->toArray() as $k => $v) {
                if ($k === 'price') {
                    $b->appendPrice($k, $v);
                }
                else {
                    $b->append($k, $v);
                }
            }
            $items[] = trim($b->getRequestString(), '[]'); // We'll let appendArray wrap the elements
        }
        return $items;
    }

    public function toJson()
    {
        // Recursively convert models to arrays
        $payload = get_object_vars($this);
        if ($this->buyer)
            $payload['buyer'] = $this->buyer->toArray();
        if ($this->shippingAddress)
            $payload['shippingAddress'] = $this->shippingAddress->toArray();
        if ($this->billingAddress)
            $payload['billingAddress'] = $this->billingAddress->toArray();
        if ($this->basketItems) {
            $payload['basketItems'] = array_map(function ($item) {
                return $item->toArray();
            }, $this->basketItems);
        }

        // Remove nulls
        $payload = array_filter($payload, function ($v) {
            return $v !== null && $v !== '';
        });

        return json_encode($payload);
    }
}
