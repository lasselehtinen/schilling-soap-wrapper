<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class Product extends SchillingSoapWrapper
{
    public function getProducts($arguments)
    {
        $query = ['ProductCriteria' => $arguments];
        return $this->request('Product', 'GetProducts', 'GetProductsRequest', $query);
    }
}
