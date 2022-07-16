<?php

namespace App\Entity;

use App\Entity\Traits\VichUploaderProfileTrait;
use App\Repository\ProfileImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProfileImageRepository::class)
 * @Vich\Uploadable
 */
class ProfileImage implements \Serializable
{
    use VichUploaderProfileTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="profileImage", cascade={"persist", "remove"})
     */
    private $owner;

    
    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    private $path;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->imageFile
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->imageFile
        ) = unserialize($serialized);
    }
}
