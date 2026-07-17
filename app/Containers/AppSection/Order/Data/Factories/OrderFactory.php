<?php

namespace App\Containers\AppSection\Order\Data\Factories;

use App\Containers\AppSection\Order\Models\Customer;
use App\Containers\AppSection\Order\Models\Order;
use App\Ship\Parents\Factories\Factory as ParentFactory;
use Illuminate\Support\Str;

class OrderFactory extends ParentFactory
{
    /**
     * @var class-string<Order>
     *
     * @psalm-suppress NonInvariantDocblockPropertyType
     */
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_code' => 'ORD-'.strtoupper(Str::random(10)),
            'delivery_date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'shipping_carrier' => $this->faker->randomElement(['GHTK', 'GHN', 'NinjaVan', 'ViettelPost']),
            'payment_method' => $this->faker->randomElement(['COD', 'CASH', 'BANK_TRANSFER', 'DEBT']),
            'debt_days' => null,
            'bank_name' => null,
            'bank_account' => null,
            'down_payment' => $this->faker->randomFloat(2, 0, 500000),
            'shipping_fee' => $this->faker->randomFloat(2, 15000, 50000),
            'status' => 'pending',
            'customer_id' => Customer::factory(),
        ];
    }
}
