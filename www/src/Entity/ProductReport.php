<?php

namespace App\Entity;

use App\Repository\ProductReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductReportRepository::class)
 */
class ProductReport
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ReportReason::class, inversedBy="productReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reason;

    /**
     * @ORM\ManyToOne(targetEntity=Sneaker::class, inversedBy="reports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

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

    public function getProduct(): ?Sneaker
    {
        return $this->product;
    }

    public function setProduct(?Sneaker $product): self
    {
        $this->product = $product;

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
