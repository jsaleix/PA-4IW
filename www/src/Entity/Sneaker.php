<?php

namespace App\Entity;

use App\Repository\SneakerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=SneakerRepository::class)
 */
class Sneaker
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
     * @ORM\Column(type="float")
     */
    private $size;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string" , length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="time")
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $unused;

    /**
     * @ORM\Column(type="boolean")
     */
    private $from_shop;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="sneakers")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="sneakers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity=SneakerModel::class, inversedBy="sneakers")
     */
    private $sneakerModel;

    /**
     * @ORM\OneToMany(targetEntity=ProductReport::class, mappedBy="product", orphanRemoval=true)
     */
    private $reports;

    /**
     * @ORM\ManyToMany(targetEntity=Color::class, inversedBy="sneakers")
     */
    private $colors;

    /**
     * @ORM\ManyToMany(targetEntity=Material::class, inversedBy="sneakers")
     */
    private $materials;

    /**
     * @ORM\OneToMany(targetEntity=ProductAppreciation::class, mappedBy="product", orphanRemoval=true)
     */
    private $productAppreciations;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="publishedSneakers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $publisher;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favoris")
     */
    private $favoris;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $StripeProductId;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="sneaker")
     */
    private $images;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sold;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->colors = new ArrayCollection();
        $this->materials = new ArrayCollection();
        $this->productAppreciations = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->images = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }


    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(float $size): self
    {
        $this->size = $size;

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

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getUnused(): ?bool
    {
        return $this->unused;
    }

    public function setUnused(bool $unused): self
    {
        $this->unused = $unused;

        return $this;
    }

    public function getFromShop(): ?bool
    {
        return $this->from_shop;
    }

    public function setFromShop(bool $from_shop): self
    {
        $this->from_shop = $from_shop;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getSneakerModel(): ?SneakerModel
    {
        return $this->sneakerModel;
    }

    public function setSneakerModel(?SneakerModel $sneakerModel): self
    {
        $this->sneakerModel = $sneakerModel;

        return $this;
    }

    /**
     * @return Collection|ProductReport[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(ProductReport $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setProduct($this);
        }

        return $this;
    }

    public function removeReport(ProductReport $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getProduct() === $this) {
                $report->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Color[]
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): self
    {
        if (!$this->colors->contains($color)) {
            $this->colors[] = $color;
        }

        return $this;
    }

    public function removeColor(Color $color): self
    {
        $this->colors->removeElement($color);

        return $this;
    }

    /**
     * @return Collection|Material[]
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Material $material): self
    {
        if (!$this->materials->contains($material)) {
            $this->materials[] = $material;
        }

        return $this;
    }

    public function removeMaterial(Material $material): self
    {
        $this->materials->removeElement($material);

        return $this;
    }

    /**
     * @return Collection|ProductAppreciation[]
     */
    public function getProductAppreciations(): Collection
    {
        return $this->productAppreciations;
    }

    public function addProductAppreciation(ProductAppreciation $productAppreciation): self
    {
        if (!$this->productAppreciations->contains($productAppreciation)) {
            $this->productAppreciations[] = $productAppreciation;
            $productAppreciation->setProduct($this);
        }

        return $this;
    }

    public function removeProductAppreciation(ProductAppreciation $productAppreciation): self
    {
        if ($this->productAppreciations->removeElement($productAppreciation)) {
            // set the owning side to null (unless already changed)
            if ($productAppreciation->getProduct() === $this) {
                $productAppreciation->setProduct(null);
            }
        }

        return $this;
    }

    public function getPublisher(): ?User
    {
        return $this->publisher;
    }

    public function setPublisher(?User $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(User $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
        }

        return $this;
    }

    public function removeFavori(User $favori): self
    {
        $this->favoris->removeElement($favori);

        return $this;
    }

    public function getStripeProductId(): ?string
    {
        return $this->StripeProductId;
    }

    public function setStripeProductId(?string $StripeProductId): self
    {
        $this->StripeProductId = $StripeProductId;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setSneaker($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getSneaker() === $this) {
                $image->setSneaker(null);
            }
        }

        return $this;
    }

    public function getSold(): ?bool
    {
        return $this->sold;
    }

    public function setSold(?bool $sold): self
    {
        $this->sold = $sold;

        return $this;
    }
}
