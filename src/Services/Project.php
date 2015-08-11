<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class Project extends SchillingSoapWrapper
{
    public function getProjects($arguments)
    {
        $query = ['WdProjectCriteria' => $arguments];
        return $this->request('Project', 'GetProjects', 'GetProjectsRequest', $query);
    }
}
