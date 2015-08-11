<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class Product extends SchillingSoapWrapper
{
    /**
     * getProducts
     * @param  array $arguments
     * @return array
     */
    public function getProducts($arguments)
    {
        $query = ['ProductCriteria' => $arguments];
        return $this->request('Product', 'GetProducts', 'GetProductsRequest', $query);
    }
}
