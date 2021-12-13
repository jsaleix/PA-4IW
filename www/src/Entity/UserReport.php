<?php

namespace App\Entity;

use App\Repository\UserReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserReportRepository::class)
 */
class UserReport
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ReportReason::class, inversedBy="userReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reason;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="userReports", orphanRemoval=true)
     */
    private $reporter;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reportedUser;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    public function __construct()
    {
        $this->reporter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?ReportReason
    {
        return $this->reason;
    }

    public function setReason(?ReportReason $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getReporter(): Collection
    {
        return $this->reporter;
    }

    public function addReporter(User $reporter): self
    {
        if (!$this->reporter->contains($reporter)) {
            $this->reporter[] = $reporter;
            $reporter->setUserReports($this);
        }

        return $this;
    }

    public function removeReporter(User $reporter): self
    {
        if ($this->reporter->removeElement($reporter)) {
            // set the owning side to null (unless already changed)
            if ($reporter->getUserReports() === $this) {
                $reporter->setUserReports(null);
            }
        }

        return $this;
    }

    public function getReportedUser(): ?User
    {
        return $this->reportedUser;
    }

    public function setReportedUser(?User $reportedUser): self
    {
        $this->reportedUser = $reportedUser;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
