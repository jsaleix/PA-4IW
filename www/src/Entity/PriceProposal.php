<?php

namespace App\Entity;

use App\Repository\PriceProposalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PriceProposalRepository::class)
 */
class PriceProposal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Sneaker::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyer;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $acceptationDate;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): self
    {
        $this->buyer = $buyer;

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

    public function getAcceptationDate(): ?\DateTimeInterface
    {
        return $this->acceptationDate;
    }

    public function setAcceptationDate(?\DateTimeInterface $acceptationDate): self
    {
        $this->acceptationDate = $acceptationDate;

        return $this;
    }
}
