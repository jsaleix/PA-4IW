<?php

namespace App\Entity;

use App\Repository\ProductAppreciationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductAppreciationRepository::class)
 */
class ProductAppreciation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Sneaker::class, inversedBy="productAppreciations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $mark;

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

    public function getMark(): ?int
    {
        return $this->mark;
    }

    public function setMark(int $mark): self
    {
        $this->mark = $mark;

        return $this;
    }
}
