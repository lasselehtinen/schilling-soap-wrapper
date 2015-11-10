<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class Order extends SchillingSoapWrapper
{
    /**
     * Used to retreive a set of orders from Schilling
     * @param  array $arguments
     * @return array
     */
    public function getOrders($arguments)
    {
        $query = ['TheCriteria' => $arguments];
        return $this->request('Order', 'GetOrders', 'GetOrdersRequest', $query);
    }
}
