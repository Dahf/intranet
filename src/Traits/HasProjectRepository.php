<?php

namespace App\Traits;

use App\Repository\ProjectRepository;

trait HasProjectRepository
{
    /**
     * @var ProjectRepository;
     */
    private $projectRepository;

    /**
     * @required
     */
    public function setProjectRepository(ProjectRepository $projectRepository): void
    {
        $this->projectRepository = $projectRepository;
    }
}