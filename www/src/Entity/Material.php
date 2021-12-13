<?php

namespace App\Entity;

use App\Repository\MaterialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaterialRepository::class)
 */
class Material
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
     * @ORM\Column(type="string", length=255)
     */
    private $icon;

    /**
     * @ORM\ManyToMany(targetEntity=Sneaker::class, mappedBy="materials")
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

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
            $sneaker->addMaterial($this);
        }

        return $this;
    }

    public function removeSneaker(Sneaker $sneaker): self
    {
        if ($this->sneakers->removeElement($sneaker)) {
            $sneaker->removeMaterial($this);
        }

        return $this;
    }
}
