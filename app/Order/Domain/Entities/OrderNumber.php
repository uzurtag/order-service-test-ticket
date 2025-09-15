<?php

namespace App\Order\Domain\Entities;

final readonly class OrderNumber
{
    private int $year;
    private int $month;
    private int $id;

    public function __construct(private string $value)
    {
        if (!preg_match('/^(?<year>\d{4})-(?<month>\d{2})-(?<id>\d+)$/', $value, $matches)) {
            throw new \InvalidArgumentException(
                'Invalid order number format, expected YYYY-MM-id'
            );
        }

        $this->year  = (int)$matches['year'];
        $this->month = (int)$matches['month'];
        $this->id    = (int)$matches['id'];

        if (strlen($this->year) !== 4) {
            throw new \InvalidArgumentException('Year must be 4 digits');
        }

        if ($this->month < 1 || $this->month > 12) {
            throw new \InvalidArgumentException('Month must be 01–12');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function month(): int
    {
        return $this->month;
    }

    public function id(): int
    {
        return $this->id;
    }

    public static function compose(int $year, int $month, int $id): self
    {
        return new self(sprintf('%04d-%02d-%d', $year, $month, $id));
    }
}
