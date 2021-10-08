<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $projectName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $projectStatus;

    /**
     * @ORM\Column(type="float")
     */
    private $projectProgress;

    /**
     * @ORM\Column(type="datetime")
     */
    private $projectTimeline;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="projects")
     */
    private $projectMember;

    public function __construct()
    {
        $this->projectMember = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function setProjectName(string $projectName): self
    {
        $this->projectName = $projectName;

        return $this;
    }

    public function getProjectStatus(): ?string
    {
        return $this->projectStatus;
    }

    public function setProjectStatus(string $projectStatus): self
    {
        $this->projectStatus = $projectStatus;

        return $this;
    }

    public function getProjectProgress(): ?float
    {
        return $this->projectProgress;
    }

    public function setProjectProgress(float $projectProgress): self
    {
        $this->projectProgress = $projectProgress;

        return $this;
    }

    public function getProjectTimeline(): ?\DateTimeInterface
    {
        return $this->projectTimeline;
    }

    public function setProjectTimeline(\DateTimeInterface $projectTimeline): self
    {
        $this->projectTimeline = $projectTimeline;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getProjectMember(): Collection
    {
        return $this->projectMember;
    }

    public function addProjectMember(User $projectMember): self
    {
        if (!$this->projectMember->contains($projectMember)) {
            $this->projectMember[] = $projectMember;
        }

        return $this;
    }

    public function removeProjectMember(User $projectMember): self
    {
        $this->projectMember->removeElement($projectMember);

        return $this;
    }
}
