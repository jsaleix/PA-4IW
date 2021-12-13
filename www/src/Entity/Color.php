<?php

namespace App\Entity;

use App\Repository\ColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ColorRepository::class)
 */
class Color
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $hexa_value;

    /**
     * @ORM\ManyToMany(targetEntity=Sneaker::class, mappedBy="colors")
     */
    private $sneakers;

    public function __construct()
    {
        $this->sneakers = new ArrayCollection();
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

    public function getHexaValue(): ?string
    {
        return $this->hexa_value;
    }

    public function setHexaValue(string $hexa_value): self
    {
        $this->hexa_value = $hexa_value;

        return $this;
    }

    /**
     * @return Collection|Sneaker[]
     */
    public function getSneakers(): Collection
    {
        return $this->sneakers;
    }

    public function addSneaker(Sneaker $sneaker): self
    {
        if (!$this->sneakers->contains($sneaker)) {
            $this->sneakers[] = $sneaker;
            $sneaker->addColor($this);
        }

        return $this;
    }

    public function removeSneaker(Sneaker $sneaker): self
    {
        if ($this->sneakers->removeElement($sneaker)) {
            $sneaker->removeColor($this);
        }

        return $this;
    }
}
