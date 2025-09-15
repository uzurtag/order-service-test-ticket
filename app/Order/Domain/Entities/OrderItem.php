<?php

namespace App\Order\Domain\Entities;

final readonly class OrderItem
{
    public function __construct(
        public int $productId,
        public float $price,
        public int $quantity
    ) {
        if ($this->price < 0 || $this->quantity < 1) {
            throw new \InvalidArgumentException('Invalid item data');
        }
    }
}
