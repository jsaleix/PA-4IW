<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagesRepository::class)
 */
class Images
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Sneaker::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Sneaker;

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

    public function getSneaker(): ?Sneaker
    {
        return $this->Sneaker;
    }

    public function setSneaker(?Sneaker $Sneaker): self
    {
        $this->Sneaker = $Sneaker;

        return $this;
    }
}
