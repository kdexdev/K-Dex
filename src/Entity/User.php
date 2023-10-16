<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "user")]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
// #[UniqueEntity(fields: ['email'], message: 'There is already an account with this email address')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Basic user information
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 90, unique: true)]
    private ?string $username = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $email = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


    // User autorization roles
    #[ORM\Column]
    private array $roles = [];


    // Database entry info properties
    #[ORM\Column(name: "created_at", type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: "last_visited_at", type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $lastVisitedAt = null;

    #[ORM\Column(name: "deleted_at", type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserProfile $userProfile = null;


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }


    /**
     * Database entry initializer
     */
    #[ORM\PrePersist]
    public function userInitializer(): User {
        // Set the creation times
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->lastVisitedAt = $now;

        // Initialize a profile
        $this->userProfile = new UserProfile();
        $this->userProfile->setUser($this);

        return $this;
    }


    /***
     * Getters and setters for all properties
     */


    public function getId(): ?int
    {
        return $this->id;
    }

    // The user's system name
    public function getUsername(): ?string
    {
        return $this->username;
    }

    // The user's system name
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    // Get the user's email
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // Set the user's email
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
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

    // Sets a JSON array of user roles
    public function setRoles(array $roles): static
    {
        $this->roles = array_unique($roles);

        return $this;
    }

    // Datetime of user account creation
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    // Datetime of latest user account activity
    public function getLastVisitedAt(): ?\DateTimeImmutable
    {
        return $this->lastVisitedAt;
    }

    // Datetime of latest user account activity
    public function setLastVisitedAt(\DateTimeImmutable $lastVisitedAt): static
    {
        $this->lastVisitedAt = $lastVisitedAt;

        return $this;
    }

    // Datetime of user account deletion
    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    // Datetime of user account deletion
    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * The getting of an encoded password string
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    // The setting of an encoded password string
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    // Getting of the assigned user profile
    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
