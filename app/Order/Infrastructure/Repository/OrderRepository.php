<?php

namespace App\Order\Infrastructure\Repository;

use App\Order\Domain\Entities\Order;
use App\Order\Domain\Entities\OrderItem;
use App\Order\Domain\Entities\OrderNumber;
use App\Order\Domain\Enums\ContractorType;
use DateMalformedStringException;
use DateTimeImmutable;
use Illuminate\Support\Collection;
use PDO;
use Throwable;

readonly class OrderRepository
{
    public function __construct(private PDO $pdo) {}

    /**
     * @param int $contractorType
     * @param float $sum
     * @param array $items
     * @return Order
     * @throws Throwable
     * @throws DateMalformedStringException
     */
    public function createOrder(int $contractorType, float $sum, array $items): Order
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO orders (contractor_type, sum, created_at, updated_at)
                 VALUES (:contractor_type, :sum, NOW(), NOW())'
            );

            $stmt->execute([
                ':contractor_type' => $contractorType,
                ':sum' => $sum,
            ]);

            $orderId = (int)$this->pdo->lastInsertId();

            $itemStmt = $this->pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, price, quantity, created_at, updated_at)
                VALUES (:order_id, :product_id, :price, :quantity, NOW(), NOW())'
            );

            foreach ($items as $item) {
                $itemStmt->execute([
                    ':order_id'   => $orderId,
                    ':product_id' => (int)$item['productId'],
                    ':price'      => (float)$item['price'],
                    ':quantity'   => (int)$item['quantity'],
                ]);
            }

            $this->pdo->commit();

            $now = new DateTimeImmutable('now');
            $orderNumber = sprintf('%s-%s-%d', $now->format('Y'), $now->format('m'), $orderId);

            return new Order(
                id: $orderId,
                orderNumber: new OrderNumber($orderNumber),
                contractorType: ContractorType::fromInt($contractorType),
                sum: $sum,
                items: collect($items)
                    ->map(fn ($item) => new OrderItem(
                        (int) $item['productId'],
                        (float) $item['price'],
                        (int) $item['quantity']
                    ))
            );

        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * @param int $limit
     * @return Collection
     */
    public function getLatestOrders(int $limit): Collection
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, contractor_type, sum, created_at
               FROM orders
           ORDER BY created_at DESC, id DESC
              LIMIT :limit'
        );

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        if (empty($orders)) {
            return collect();
        }

        $orderIds = array_map(fn($order) => (int)$order['id'], $orders);

        $placeholdersIds = implode(',', array_fill(0, count($orderIds), '?'));
        $itemsStmt = $this->pdo->prepare(
            "SELECT order_id, product_id, price, quantity
                    FROM order_items
                    WHERE order_id IN ($placeholdersIds)
                    ORDER BY id ASC"
        );

        foreach ($orderIds as $index => $value) {
            $itemsStmt->bindValue($index + 1, $value, PDO::PARAM_INT);
        }

        $itemsStmt->execute();
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $itemsByOrder = [];
        foreach ($items as $item) {
            $orderId = (int)$item['order_id'];
            $itemsByOrder[$orderId][] = $item;
        }

        return collect($orders)->map(function (array $order) use ($itemsByOrder): Order {
            $id = (int)$order['id'];
            $items = $itemsByOrder[$id] ?? [];
            $createdAt = new DateTimeImmutable($order['created_at']);
            $orderNumber = OrderNumber::compose(
                (int)$createdAt->format('Y'),
                (int)$createdAt->format('m'),
                $id
            );

            return new Order(
                id: $id,
                orderNumber: new OrderNumber($orderNumber),
                contractorType: ContractorType::fromInt((int)$order['contractor_type']),
                sum: (float)$order['sum'],
                items: collect($items)->map(fn ($item) => new OrderItem(
                    (int)$item['product_id'],
                    (float)$item['price'],
                    (int)$item['quantity']
                ))
            );
        });
    }
}
