<?php

declare(strict_types=1);

namespace App\Order\Domain\Enums;

enum ContractorType: int
{
    case PERSON = 1;
    case LEGAL = 2;

    public static function fromInt(int $value): self {
        return match($value) {
            1 => self::PERSON,
            2 => self::LEGAL,
            default => throw new \InvalidArgumentException('Unknown contractor type: '. $value)
        };
    }
}
