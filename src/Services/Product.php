<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class Product extends SchillingSoapWrapper
{
    /**
     * Returns a list of discount information that matches the criteria.
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     */
    public function getDiscountInformation($arguments)
    {
        $query = ['DiscountCriteria' => $arguments];
        return $this->request('Product', 'GetDiscountInformation', 'GetDiscountInformationRequest', $query);
    }

    /**
     * Returns a list of Internet categories.
     * @param  array $arguments
     * @return array
     */
    public function getInternetCategories($arguments)
    {
        $query = ['Criteria' => $arguments];
        return $this->request('Product', 'GetInternetCategories', 'GetInternetCategoriesRequest', $query);
    }

    /**
     * Returns the entire list of Internet categories. Use getInternetCategories you want to use criterias.
     * @return array
     */
    public function getProductInternetCategories()
    {
        return $this->request('Product', 'GetProductInternetCategories', 'GetProductInternetCategoriesRequest');
    }

    /**
     * Used to retreive one or more products from Schilling.
     * @param  array $arguments
     * @return array
     */
    public function getProducts($arguments)
    {
        $query = ['ProductCriteria' => $arguments];
        return $this->request('Product', 'GetProducts', 'GetProductsRequest', $query);
    }
}
