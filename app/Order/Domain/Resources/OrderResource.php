<?php

namespace App\Order\Domain\Resources;

use App\Order\Domain\Entities\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (string) $this->resource->orderNumber,
            'sum' => $this->resource->sum,
            'contractorType' => $this->resource->contractorType->value,
            'items' => $this->resource->items->map(fn($item) => [
                'productId' => $item->productId,
                'price' => $item->price,
                'quantity' => $item->quantity,
            ])->values()->all(),
        ];
    }
}
