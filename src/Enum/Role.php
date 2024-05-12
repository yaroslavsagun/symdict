<?php

namespace App\Enum;

enum Role: int
{
    case ADMIN = 1;
    case USER = 2;

    public function slug(): string
    {
        return match ($this) {
            self::ADMIN => 'ROLE_ADMIN',
            self::USER => 'ROLE_USER'
        };
    }
}