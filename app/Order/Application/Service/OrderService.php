<?php

namespace App\Order\Application\Service;

use App\Order\Domain\Dto\OrderDto;
use App\Order\Domain\Resources\OrderCollection;
use App\Order\Infrastructure\Repository\OrderRepository;
use Throwable;

readonly class OrderService
{
    public function __construct(private OrderRepository $orderRepository) {}

    /**
     * @param OrderDto $dto
     * @return string
     * @throws Throwable
     */
    public function create(OrderDto $dto): string
    {
        $order = $this->orderRepository->createOrder(
            contractorType: (int)$dto->contractorType,
            sum: (float)$dto->sum,
            items: $dto->items
        );

        return $order->paymentRedirectUrl();
    }

    /**
     * @param int $limit
     * @return OrderCollection
     */
    public function getLast(int $limit): OrderCollection
    {
        return new OrderCollection($this->orderRepository->getLatestOrders($limit));
    }
}
