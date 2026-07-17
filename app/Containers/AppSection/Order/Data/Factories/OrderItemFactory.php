<?php

namespace App\Containers\AppSection\Order\Data\Factories;

use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Order\Models\OrderItem;
use App\Containers\AppSection\Order\Models\Product;
use App\Ship\Parents\Factories\Factory as ParentFactory;

class OrderItemFactory extends ParentFactory
{
    /**
     * @var class-string<OrderItem>
     *
     * @psalm-suppress NonInvariantDocblockPropertyType
     */
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_name' => $this->faker->words(3, true),
            'price' => $this->faker->randomFloat(2, 10000, 1000000),
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}
