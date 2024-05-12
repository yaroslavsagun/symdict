<?php

namespace App\Request\Translation;

use App\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints\NotBlank;

class GetTranslationRequest extends AbstractRequest
{
    #[NotBlank]
    public readonly int $originalLanguage;

    #[NotBlank]
    public readonly int $originalWord;

    #[NotBlank]
    public readonly int $translationLanguage;
}