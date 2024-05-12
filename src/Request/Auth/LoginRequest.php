<?php

namespace App\Request\Auth;

use App\Request\AbstractRequest;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class LoginRequest extends AbstractRequest
{
    #[NotBlank]
    #[Type(Types::STRING)]
    public readonly string $login;

    #[NotBlank]
    public readonly string $password;
}