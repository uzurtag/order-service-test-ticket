<?php

namespace App\Order\Presentation\Controllers;

use App\Order\Application\Service\OrderService;
use App\Order\Domain\Dto\OrderDto;
use App\Order\Domain\Resources\OrderCollection;
use App\Order\Presentation\Request\OrderCreateRequest;
use App\Order\Presentation\Request\OrdersLatestRequest;
use Illuminate\Http\RedirectResponse;
use Throwable;

class OrderController
{
    public function __construct(protected OrderService $orderService) {}

    /**
     * @param OrderCreateRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function create(OrderCreateRequest $request): RedirectResponse
    {
        return new RedirectResponse($this->orderService->create(OrderDto::fromRequest($request->toArray())));
    }

    /**
     * @param OrdersLatestRequest $request
     * @return OrderCollection
     */
    public function last(OrdersLatestRequest $request): OrderCollection
    {
        return $this->orderService->getLast($request->limit());
    }
}
