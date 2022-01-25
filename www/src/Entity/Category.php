<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mainCategory;

    /**
     * @ORM\ManyToMany(targetEntity=Sneaker::class, mappedBy="Category")
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

    public function getMainCategory(): ?bool
    {
        return $this->mainCategory;
    }

    public function setMainCategory(bool $mainCategory): self
    {
        $this->mainCategory = $mainCategory;

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
            $sneaker->addCategory($this);
        }

        return $this;
    }

    public function removeSneaker(Sneaker $sneaker): self
    {
        if ($this->sneakers->removeElement($sneaker)) {
            $sneaker->removeCategory($this);
        }

        return $this;
    }
}
