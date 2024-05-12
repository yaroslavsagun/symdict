<?php

namespace App\Entity;

use App\Enum\Role;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
#[ORM\Table('users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $role_id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(unique: true)]
    private string $email;

    #[Ignore]
    #[ORM\Column]
    private string $password;

    #[Ignore]
    #[ORM\OneToMany(AccessToken::class, mappedBy: 'user')]
    private ?Collection $tokens = null;

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setRole(Role $role): self
    {
        $this->role_id = $role->value;

        return $this;
    }

    public function getRole(): Role
    {
        return Role::from($this->role_id);
    }

    #[Ignore]
    public function getRoles(): array
    {
        return [$this->getRole()->slug()];
    }

    public function eraseCredentials(): void
    {
    }

    #[Ignore]
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getTokens(): Collection
    {
        return $this->tokens;
    }
}
