<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    const ROLES = array(
        'roles.admin' => 'ROLE_ADMIN',
        'roles.seller' => 'ROLE_SELLER',
        'roles.user' => 'ROLE_USER'
    );

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeConnectId;

    /**
     * @ORM\Column(type="string", length=110, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $surname;

    /**
     * @ORM\OneToMany(targetEntity=Invoice::class, mappedBy="buyer", orphanRemoval=true)
     */
    private $invoices;

    /**
     * @ORM\OneToMany(targetEntity=Sneaker::class, mappedBy="publisher", orphanRemoval=true)
     */
    private $publishedSneakers;

    /**
     * @ORM\OneToMany(targetEntity=UserReport::class, mappedBy="reported", orphanRemoval=true)
     */
    private $userReports;

    /**
     * @ORM\OneToMany(targetEntity=UserReport::class, mappedBy="reporter")
     */
    private $userReportsMade;

    /**
     * @ORM\ManyToMany(targetEntity=Conversation::class, mappedBy="users")
     */
    private $conversations;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
        $this->publishedSneakers = new ArrayCollection();
        $this->userReports = new ArrayCollection();
        $this->userReportsMade = new ArrayCollection();
        $this->conversations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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

    public function getStripeConnectId(): ?string
    {
        return $this->stripeConnectId;
    }

    public function setStripeConnectId(?string $stripeConnectId): self
    {
        $this->stripeConnectId = $stripeConnectId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

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
    public function getPublishedSneakers(): Collection
    {
        return $this->publishedSneakers;
    }

    public function addPublishedSneaker(Sneaker $publishedSneaker): self
    {
        if (!$this->publishedSneakers->contains($publishedSneaker)) {
            $this->publishedSneakers[] = $publishedSneaker;
            $publishedSneaker->setPublisher($this);
        }

        return $this;
    }

    public function removePublishedSneaker(Sneaker $publishedSneaker): self
    {
        if ($this->publishedSneakers->removeElement($publishedSneaker)) {
            // set the owning side to null (unless already changed)
            if ($publishedSneaker->getPublisher() === $this) {
                $publishedSneaker->setPublisher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserReport[]
     */
    public function getUserReports(): Collection
    {
        return $this->userReports;
    }

    public function addUserReport(UserReport $userReport): self
    {
        if (!$this->userReports->contains($userReport)) {
            $this->userReports[] = $userReport;
            $userReport->setReported($this);
        }

        return $this;
    }

    public function removeUserReport(UserReport $userReport): self
    {
        if ($this->userReports->removeElement($userReport)) {
            // set the owning side to null (unless already changed)
            if ($userReport->getReported() === $this) {
                $userReport->setReported(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserReport[]
     */
    public function getUserReportsMade(): Collection
    {
        return $this->userReportsMade;
    }

    public function addUserReportsMade(UserReport $userReportsMade): self
    {
        if (!$this->userReportsMade->contains($userReportsMade)) {
            $this->userReportsMade[] = $userReportsMade;
            $userReportsMade->setReporter($this);
        }

        return $this;
    }

    public function removeUserReportsMade(UserReport $userReportsMade): self
    {
        if ($this->userReportsMade->removeElement($userReportsMade)) {
            // set the owning side to null (unless already changed)
            if ($userReportsMade->getReporter() === $this) {
                $userReportsMade->setReporter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->addUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            $conversation->removeUser($this);
        }

        return $this;
    }
}
