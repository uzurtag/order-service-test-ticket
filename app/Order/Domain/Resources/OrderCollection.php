<?php

namespace App\Order\Domain\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    public static $wrap = '';

    public function toArray($request): array
    {
        return $this->collection->map(fn($order) => new OrderResource($order))->all();
    }
}
