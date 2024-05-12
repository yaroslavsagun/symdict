<?php

namespace App\Request\Account;

use App\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints\Email;

class SaveAccountRequest extends AbstractRequest
{
    #[Email]
    public ?string $email = null;

    public ?string $name = null;
}