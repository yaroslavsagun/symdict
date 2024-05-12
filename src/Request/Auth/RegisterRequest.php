<?php

namespace App\Request\Auth;

use App\Request\AbstractRequest;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\Type;

class RegisterRequest extends AbstractRequest
{
    #[NotBlank]
    #[Length(min: 3, max: 20)]
    #[Type(Types::STRING)]
    public readonly string $name;

    #[NotBlank]
    #[Email]
    public readonly string $email;

    #[NotBlank]
    public readonly string $password;
}