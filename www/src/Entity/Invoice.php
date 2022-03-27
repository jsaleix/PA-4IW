<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 */
class Invoice
{
    const PENDING_STATUS = 'pending';
    const SOLD_STATUS = 'paid';
    const DELIVERING_STATUS = 'out for delivery';
    const FINISHED_STATUS = 'finished';

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
    private $sneaker;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $paymentStatus;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="invoices")
     * @ORM\JoinColumn(nullable=true)
     */
    private $buyer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripePI;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trackingNb;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $receptionAddress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSneaker(): ?Sneaker
    {
        return $this->sneaker;
    }

    public function setSneaker(?Sneaker $sneaker): self
    {
        $this->sneaker = $sneaker;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getStripePI(): ?string
    {
        return $this->stripePI;
    }

    public function setStripePI(?string $stripePI): self
    {
        $this->stripePI = $stripePI;

        return $this;
    }

    public function getTrackingNb(): ?string
    {
        return $this->trackingNb;
    }

    public function setTrackingNb(?string $trackingNb): self
    {
        $this->trackingNb = $trackingNb;

        return $this;
    }

    public function getReceptionAddress(): ?string
    {
        return $this->receptionAddress;
    }

    public function setReceptionAddress(string $receptionAddress): self
    {
        $this->receptionAddress = $receptionAddress;

        return $this;
    }

}
