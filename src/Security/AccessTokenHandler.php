<?php

namespace App\Security;

use App\Repository\AccessTokenRepository;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(private readonly AccessTokenRepository $accessTokenRepository)
    {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $token = $this->accessTokenRepository->findOneBy(['token' => $accessToken]);

        if (!$token) {
            throw new BadCredentialsException('incorrect token');
        }

        $now = new DateTimeImmutable();
        if ($token->getExpiresAt() && $token->getExpiresAt() < $now) {
            throw new BadCredentialsException('token expired');
        }

        return new UserBadge($token->getUser()->getUserIdentifier());
    }
}