<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class ProjectHierarchy extends SchillingSoapWrapper
{
    /**
     * Returns related products to the ProductNumber
     * @param  string $productNumber
     * @return array
     */
    public function getRelatedProductsByProductNumber($productNumber)
    {
        $query = ['ProductNumber' => $productNumber];
        return $this->request('ProjectHierarchy', 'GetRelatedProductsByProductNumber', 'GetRelatedProductsByProductNumberRequest', $query);
    }

    /**
     * Returns the title hierarchy that ProjectNumber belongs to
     * @param  string $projectNumber
     * @return array
     */
    public function getTitleHierarchyByProjectNumber($projectNumber)
    {
        $query = ['ProjectNumber' => $projectNumber];
        return $this->request('ProjectHierarchy', 'GetTitleHierarchyByProjectNumber', 'GetTitleHierarchyByProjectNumberRequest', $query);
    }
}
