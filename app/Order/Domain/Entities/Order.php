<?php

namespace App\Order\Domain\Entities;

use App\Order\Domain\Enums\ContractorType;
use Illuminate\Support\Collection;

final readonly class Order
{
    /** @param Collection<int, OrderItem> $items */
    public function __construct(
        public ?int $id,
        public OrderNumber $orderNumber,
        public ContractorType $contractorType,
        public float $sum,
        public Collection $items
    ) {
        if ($this->sum < 0) {
            throw new \InvalidArgumentException('Sum must be >= 0');
        }

        if ($this->items->isEmpty()) {
            throw new \InvalidArgumentException('Items required');
        }
    }

    public function paymentRedirectUrl(): string
    {
        $base = config('payment.base_url');

        return match ($this->contractorType) {
            ContractorType::PERSON => "{$base}/pay/{$this->orderNumber}",
            ContractorType::LEGAL => "{$base}/orders/{$this->orderNumber}/bill",
        };
    }
}
