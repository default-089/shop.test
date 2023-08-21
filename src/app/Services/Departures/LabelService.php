<?php

namespace App\Services\Departures;

use App\Models\Orders\Order;

class LabelService
{
    /**
     * Create label
     */
    public function createLabel(int $orderId): string
    {
        $order = Order::where('id', $orderId)->with([
            'itemsExtended.installment',
            'onlinePayments',
            'delivery',
            'user' => fn ($query) => $query->with('lastAddress')
        ])->first();
        $labelService = new BelpostLabelService;

        return $labelService->createLabel($order);
    }
}
