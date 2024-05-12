<?php

namespace App\Request\Word;

use App\Request\AbstractRequest;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UpdateWordRequest extends AbstractRequest
{
    #[NotBlank]
    #[Type(Types::STRING)]
    public readonly string $word;
}