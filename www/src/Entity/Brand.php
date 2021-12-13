<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 */
class Brand
{
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity=Sneaker::class, mappedBy="brand", orphanRemoval=true)
     */
    private $sneakers;

    /**
     * @ORM\OneToMany(targetEntity=SneakerModel::class, mappedBy="brand", orphanRemoval=true)
     */
    private $sneakerModels;

    public function __construct()
    {
        $this->sneakers = new ArrayCollection();
        $this->sneakerModels = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

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
            $sneaker->setBrand($this);
        }

        return $this;
    }

    public function removeSneaker(Sneaker $sneaker): self
    {
        if ($this->sneakers->removeElement($sneaker)) {
            // set the owning side to null (unless already changed)
            if ($sneaker->getBrand() === $this) {
                $sneaker->setBrand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SneakerModel[]
     */
    public function getSneakerModels(): Collection
    {
        return $this->sneakerModels;
    }

    public function addSneakerModel(SneakerModel $sneakerModel): self
    {
        if (!$this->sneakerModels->contains($sneakerModel)) {
            $this->sneakerModels[] = $sneakerModel;
            $sneakerModel->setBrand($this);
        }

        return $this;
    }

    public function removeSneakerModel(SneakerModel $sneakerModel): self
    {
        if ($this->sneakerModels->removeElement($sneakerModel)) {
            // set the owning side to null (unless already changed)
            if ($sneakerModel->getBrand() === $this) {
                $sneakerModel->setBrand(null);
            }
        }

        return $this;
    }
}
