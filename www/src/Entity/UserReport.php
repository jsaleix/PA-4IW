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
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reported;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userReportsMade")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reporter;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getReported(): ?User
    {
        return $this->reported;
    }

    public function setReported(?User $reported): self
    {
        $this->reported = $reported;

        return $this;
    }

    public function getReporter(): ?User
    {
        return $this->reporter;
    }

    public function setReporter(?User $reporter): self
    {
        $this->reporter = $reporter;

        return $this;
    }
}
