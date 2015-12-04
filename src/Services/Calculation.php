<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class Calculation extends SchillingSoapWrapper
{
    /**
     * Used to retreive a set of orders from Schilling
     * @param  array $arguments
     * @return array
     */
    public function getCalculation($arguments)
    {
        $query = ['CalculationCriteria' => $arguments];
        return $this->request('Calculation', 'GetCalculation', 'GetCalculationRequest', $query);
    }
}
