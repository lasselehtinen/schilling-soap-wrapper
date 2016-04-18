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

    /**
     * Used to create or update a debtor in Schilling
     * @param  array $arguments
     * @return array
     */
    public function saveDebtor($arguments)
    {
        $query = ['DebtorData' => $arguments];
        return $this->request('Debtor', 'SaveDebtor', 'SaveDebtorRequest', $query);
    }
}
