<?php

namespace App\Entity;

use App\Repository\AccessTokenRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\MaxDepth;

#[ORM\Entity(repositoryClass: AccessTokenRepository::class)]
#[ORM\Table('access_tokens')]
class AccessToken
{
    private const TOKEN_PREFIX = 'sdt_';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $token;

    #[ORM\ManyToOne(inversedBy: 'tokens')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $expires_at = null;

    public function __construct()
    {
        $token = '';
        for ($i = 0; $i < 3; $i++) {
            $token .= md5(time().mt_rand(0, 1000));
        }
        $this->token = self::TOKEN_PREFIX.$token;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        //$this->user_id = $user->getId();
        $this->user = $user;

        return $this;
    }

//    public function getUserId(): ?int
//    {
//        return $this->user_id;
//    }
//
//    public function setUserId(int $user_id): static
//    {
//        $this->user_id = $user_id;
//
//        return $this;
//    }

    public function getExpiresAt(): ?DateTimeImmutable
    {
        return $this->expires_at;
    }

    public function setExpiresAt(?DateTimeImmutable $expires_at): static
    {
        $this->expires_at = $expires_at;

        return $this;
    }
}
