<?php

namespace App\Containers\AppSection\Order\UI\API\Transformers;

use App\Containers\AppSection\Order\Models\Order;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class OrderTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [

    ];

    protected array $availableIncludes = [

    ];

    public function transform(Order $order): array
    {
        $response = [
            'object' => $order->getResourceKey(),
            'id' => $order->getHashedKey(),
            'order_code' => $order->order_code,
            'customer_id' => $order->customer ? $order->customer->getHashedKey() : null,
            'delivery_date' => $order->delivery_date ? $order->delivery_date->format('Y-m-d') : null,
            'shipping_carrier' => $order->shipping_carrier,
            'payment_method' => $order->payment_method,
            'debt_days' => $order->debt_days,
            'bank_name' => $order->bank_name,
            'bank_account' => $order->bank_account,
            'down_payment' => $order->down_payment,
            'shipping_fee' => $order->shipping_fee,
            'status' => $order->status,
        ];

        return $this->ifAdmin([
            'real_id' => $order->id,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'readable_created_at' => $order->created_at->diffForHumans(),
            'readable_updated_at' => $order->updated_at->diffForHumans(),
            // 'deleted_at' => $order->deleted_at,
        ], $response);
    }
}
