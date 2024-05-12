<?php

namespace App\Request\Language;

use App\Request\AbstractRequest;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UpdateLanguageRequest extends AbstractRequest
{
    #[NotBlank]
    #[Type(Types::STRING)]
    public readonly string $slug;

    #[NotBlank]
    #[Type(Types::STRING)]
    public readonly string $name;
}