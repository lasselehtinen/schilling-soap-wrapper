<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class TextHandling extends SchillingSoapWrapper
{
    /**
     * Used to retrieve one or more TextHandlings from Schilling.
     * @param  array $arguments
     * @return array
     */
    public function getTextHandlings($arguments)
    {
        $query = ['TextHandlingCriteria' => $arguments];
        return $this->request('TextHandling', 'GetTextHandlings', 'GetTextHandlingsRequest', $query);
    }
}
