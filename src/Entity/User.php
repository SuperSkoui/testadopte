<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $commitmentStart = null;

    #[ORM\Column(nullable: true)]
    private ?bool $commitmentActive = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Commitment $commitment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cvv = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $card_number = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lasPaymentDate = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Payment $payment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCommitmentStart(): ?\DateTimeInterface
    {
        return $this->commitmentStart;
    }

    public function setCommitmentStart(?\DateTimeInterface $commitmentStart): static
    {
        $this->commitmentStart = $commitmentStart;

        return $this;
    }

    public function isCommitmentActive(): ?bool
    {
        return $this->commitmentActive;
    }

    public function setCommitmentActive(?bool $commitmentActive): static
    {
        $this->commitmentActive = $commitmentActive;

        return $this;
    }

    public function getCommitment(): ?Commitment
    {
        return $this->commitment;
    }

    public function setCommitment(?Commitment $commitment): static
    {
        $this->commitment = $commitment;

        return $this;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(?string $cvv): static
    {
        $this->cvv = $cvv;

        return $this;
    }

    public function getCardNumber(): ?string
    {
        return $this->card_number;
    }

    public function setCardNumber(?string $card_number): static
    {
        $this->card_number = $card_number;

        return $this;
    }

    public function getLasPaymentDate(): ?\DateTimeInterface
    {
        return $this->lasPaymentDate;
    }

    public function setLasPaymentDate(?\DateTimeInterface $lasPaymentDate): static
    {
        $this->lasPaymentDate = $lasPaymentDate;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        // unset the owning side of the relation if necessary
        if ($payment === null && $this->payment !== null) {
            $this->payment->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($payment !== null && $payment->getUser() !== $this) {
            $payment->setUser($this);
        }

        $this->payment = $payment;

        return $this;
    }
}
