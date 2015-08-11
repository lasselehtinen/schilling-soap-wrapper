<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class Lookup extends SchillingSoapWrapper
{
    public function lookup($arguments)
    {
        $query = ['Criteria' => $arguments];
        return $this->request('Lookup', 'Lookup', 'LookupRequest', $query);
    }
}
