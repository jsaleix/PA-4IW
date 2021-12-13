<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=110)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=110)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="date")
     */
    private $joinDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idStripeConnect;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity=ProductReport::class, mappedBy="reporter", orphanRemoval=true)
     */
    private $productReports;

    /**
     * @ORM\OneToMany(targetEntity=Invoice::class, mappedBy="buyer", orphanRemoval=true)
     */
    private $invoices;

    /**
     * @ORM\OneToMany(targetEntity=Sneaker::class, mappedBy="publisher")
     */
    private $sneakers;

    /**
     * @ORM\ManyToOne(targetEntity=UserReport::class, inversedBy="reporter")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userReports;

    public function __construct()
    {
        $this->productReports = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->sneakers = new ArrayCollection();
        $this->userReports = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getJoinDate(): ?\DateTimeInterface
    {
        return $this->joinDate;
    }

    public function setJoinDate(\DateTimeInterface $joinDate): self
    {
        $this->joinDate = $joinDate;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIdStripeConnect(): ?string
    {
        return $this->idStripeConnect;
    }

    public function setIdStripeConnect(?string $idStripeConnect): self
    {
        $this->idStripeConnect = $idStripeConnect;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|ProductReport[]
     */
    public function getProductReports(): Collection
    {
        return $this->productReports;
    }

    public function addProductReport(ProductReport $productReport): self
    {
        if (!$this->productReports->contains($productReport)) {
            $this->productReports[] = $productReport;
            $productReport->setReporter($this);
        }

        return $this;
    }

    public function removeProductReport(ProductReport $productReport): self
    {
        if ($this->productReports->removeElement($productReport)) {
            // set the owning side to null (unless already changed)
            if ($productReport->getReporter() === $this) {
                $productReport->setReporter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setBuyer($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getBuyer() === $this) {
                $invoice->setBuyer(null);
            }
        }

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
            $sneaker->setPublisher($this);
        }

        return $this;
    }

    public function removeSneaker(Sneaker $sneaker): self
    {
        if ($this->sneakers->removeElement($sneaker)) {
            // set the owning side to null (unless already changed)
            if ($sneaker->getPublisher() === $this) {
                $sneaker->setPublisher(null);
            }
        }

        return $this;
    }

    public function getUserReports(): ?UserReport
    {
        return $this->userReports;
    }

    public function setUserReports(?UserReport $userReports): self
    {
        $this->userReports = $userReports;

        return $this;
    }

    public function addUserReport(UserReport $userReport): self
    {
        if (!$this->userReports->contains($userReport)) {
            $this->userReports[] = $userReport;
            $userReport->setReportedUser($this);
        }

        return $this;
    }

    public function removeUserReport(UserReport $userReport): self
    {
        if ($this->userReports->removeElement($userReport)) {
            // set the owning side to null (unless already changed)
            if ($userReport->getReportedUser() === $this) {
                $userReport->setReportedUser(null);
            }
        }

        return $this;
    }
}
