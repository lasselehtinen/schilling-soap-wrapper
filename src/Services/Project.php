<?php namespace LasseLehtinen\SchillingSoapWrapper\Services;

use LasseLehtinen\SchillingSoapWrapper\SchillingSoapWrapper;

class Project extends SchillingSoapWrapper
{
    /**
     * Used to retrieve one or more Project from Schilling
     * @param  array $arguments
     * @return array
     */
    public function getProjects($arguments)
    {
        $query = ['WdProjectCriteria' => $arguments];
        return $this->request('Project', 'GetProjects', 'GetProjectsRequest', $query);
    }

    /**
     * Used to create/update Projects in Schilling.
     * @param  array $arguments
     * @return array
     */
    public function saveProjects($arguments)
    {
        $query = ['Project' => $arguments];
        return $this->request('Project', 'SaveProjects', 'SaveProjectsRequest', $query);
    }
}
