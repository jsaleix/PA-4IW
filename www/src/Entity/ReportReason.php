<?php

namespace App\Entity;

use App\Repository\ReportReasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportReasonRepository::class)
 */
class ReportReason
{

    const TYPE_PRODUCT = "PRODUCT";
    const TYPE_USER = "USER";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=ProductReport::class, mappedBy="reason")
     */
    private $productReports;

    /**
     * @ORM\OneToMany(targetEntity=UserReport::class, mappedBy="reason", orphanRemoval=true)
     */
    private $userReports;

    public function __construct()
    {
        $this->productReports = new ArrayCollection();
        $this->userReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|ProductReport[]
     */
    public function getProductReports(): Collection
    {
        return $this->productReports;
    }

    public function addProductReport(ProductReport $productReport): self
    {
        if (!$this->productReports->contains($productReport)) {
            $this->productReports[] = $productReport;
            $productReport->setReason($this);
        }

        return $this;
    }

    public function removeProductReport(ProductReport $productReport): self
    {
        if ($this->productReports->removeElement($productReport)) {
            // set the owning side to null (unless already changed)
            if ($productReport->getReason() === $this) {
                $productReport->setReason(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserReport[]
     */
    public function getUserReports(): Collection
    {
        return $this->userReports;
    }

    public function addUserReport(UserReport $userReport): self
    {
        if (!$this->userReports->contains($userReport)) {
            $this->userReports[] = $userReport;
            $userReport->setReason($this);
        }

        return $this;
    }

    public function removeUserReport(UserReport $userReport): self
    {
        if ($this->userReports->removeElement($userReport)) {
            // set the owning side to null (unless already changed)
            if ($userReport->getReason() === $this) {
                $userReport->setReason(null);
            }
        }

        return $this;
    }
}
