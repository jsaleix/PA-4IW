<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\SerializerInterface;

trait VichUploaderProfileTrait
{
    /**
     * @Vich\UploadableField(mapping="profiles", fileNameProperty="imagePath")
     * @Assert\Image(mimeTypes="image/jpeg", maxSize="1000k", maxSizeMessage="Taille autorisé : {{ limit }}{{ suffix }} alors que votre fichier fait {{ size }}{{ suffix }}")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

}