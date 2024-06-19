<?php

namespace App\Observers;

use App\Models\Orders\OrderItem;
use App\Models\Orders\OrderItemExtended;
use App\Models\Size;
use App\Services\LogService;
use App\Services\Order\OrderItemInventoryService;

class OrderItemObserver
{
    /**
     * OrderItemObserver constructor.
     */
    public function __construct(private LogService $logService) {}

    /**
     * Handle the OrderItem "created" event.
     */
    public function created(OrderItem $orderItem): void
    {
        if ($orderItem instanceof OrderItemExtended) {
            if ($orderItem->order->created_at->addSeconds(5)->isPast()) {
                $this->logService->logOrderAction($orderItem->order_id, "Товар {$orderItem->product_id} добавлен к заказу");
            }
        }
    }

    /**
     * Handle the OrderItem "saving" event.
     */
    public function saving(OrderItem $orderItem): void
    {
        if ($orderItem->isDirty('status_key')) {
            $orderItem->status_updated_at = now();

            if ($orderItem->status_key !== 'new') {
                $this->logService->logOrderAction($orderItem->order_id, "Товару {$orderItem->product_id} присвоен статус “{$orderItem->status_key}”");
            }
        }
    }

    /**
     * Handle the OrderItem "saved" event.
     */
    public function saved(OrderItem $orderItem): void
    {
        if ($orderItem->isDirty('status_key')) {
            (new OrderItemInventoryService)->handleChangeItemStatus($orderItem->refresh());
        }
    }

    /**
     * Handle the OrderItem "updating" event.
     */
    public function updating(OrderItem $orderItem): void
    {
        if ($orderItem->isDirty('size_id')) {
            $oldSize = Size::query()->find($orderItem->getOriginal('size_id'))?->name;
            $newSize = Size::query()->find($orderItem->size_id)?->name;
            $this->logService->logOrderAction(
                $orderItem->order_id,
                "В товаре {$orderItem->product_id} изменен размер “{$oldSize}” &rarr; “{$newSize}”"
            );
        }
    }

    /**
     * Handle the OrderItem "deleting" event.
     */
    public function deleting(OrderItem $orderItem): void
    {
        $this->logService->logOrderAction($orderItem->order_id, "Товар {$orderItem->product_id} удален из заказа");
    }
}
