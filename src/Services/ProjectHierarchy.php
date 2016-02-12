<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class ProjectHierarchy extends SchillingSoapWrapper
{
    /**
     * Used to retreive a set of orders from Schilling
     * @param  array $arguments
     * @return array
     */
    public function getRelatedProductsByProductNumber($productNumber)
    {
        $query = ['ProductNumber' => $productNumber];
        return $this->request('ProjectHierarchy', 'GetRelatedProductsByProductNumber', 'GetRelatedProductsByProductNumberRequest', $query);
    }
}
