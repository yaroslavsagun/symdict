<?php

namespace App\Request\Translation;

use App\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateTranslationRequest extends AbstractRequest
{
    #[NotBlank]
    public readonly int $originalWordId;

    #[NotBlank]
    public readonly int $translationWordId;
}