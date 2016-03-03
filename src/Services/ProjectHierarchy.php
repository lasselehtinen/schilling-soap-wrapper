<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class ProjectHierarchy extends SchillingSoapWrapper
{
    /**
     * Returns related products to the ProductNumber
     * @param  array $arguments
     * @return array
     */
    public function getRelatedProductsByProductNumber($productNumber)
    {
        $query = ['ProductNumber' => $productNumber];
        return $this->request('ProjectHierarchy', 'GetRelatedProductsByProductNumber', 'GetRelatedProductsByProductNumberRequest', $query);
    }

    /**
     * Returns the title hierarchy that ProjectNumber belongs to
     * @param  array $arguments
     * @return array
     */
    public function GetTitleHierarchyByProjectNumber($projectNumber)
    {
        $query = ['ProjectNumber' => $projectNumber];
        return $this->request('ProjectHierarchy', 'GetTitleHierarchyByProjectNumber', 'GetTitleHierarchyByProjectNumberRequest', $query);
    }
}
