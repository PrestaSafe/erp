<?php

namespace Prestasafe\Erp\Classes;

use Prestasafe\Erp\Models\Tax;

class Calculator{

    public $ht;
    public $ttc;
    public $quantity;
    public $tax;
    public $discount;


    public function __construct($ht, Tax $tax = null, $quantity = 0, $discount = 0)
    {
        $this->ht = $ht;
        $this->tax = $tax;
        $this->quantity = $quantity;
        $this->discount = $discount;
    }


    public function getPriceHT()
    {
       return $this->ht = $this->format($this->ht);
    }

    private function format($price)
    {
        return round($price,2);
    }

    public function getPriceTTC()
    {
        return $this->ttc = $this->format($this->ht * $this->tax->ratio,2); 
    }

    public function calculatePriceWithDiscount($price,$quantity,$discount_percent)
    {
        return ($discount_percent>0) ? $price * $quantity * (1-($discount_percent/100)) : $price * $quantity;
    }


}