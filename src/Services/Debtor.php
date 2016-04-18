<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class Debtor extends SchillingSoapWrapper
{
    /**
     * Used to retrieve one or more Debtors from Schilling
     * @param  array $arguments
     * @return array
     */
    public function getDebtors($arguments)
    {
        $query = ['DebtorCriteria' => $arguments];
        return $this->request('Debtor', 'GetDebtors', 'GetDebtorsRequest', $query);
    }
}
