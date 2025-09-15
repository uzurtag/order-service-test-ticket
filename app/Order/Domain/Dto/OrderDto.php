<?php

namespace App\Order\Domain\Dto;

readonly class OrderDto
{
    public function __construct(
        public string $sum,
        public string $contractorType,
        public array  $items
    ) {}

    public static function fromRequest(array $data): OrderDto
    {
        return new self(
            sum: $data['sum'],
            contractorType: $data['contractorType'],
            items: $data['items']
        );
    }
}
