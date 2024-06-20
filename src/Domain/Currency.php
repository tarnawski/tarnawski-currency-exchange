<?php

declare(strict_types=1);

namespace App\Domain;

enum Currency: string
{
    case EUR = 'EUR';
    case GBP = 'GBP';
}
