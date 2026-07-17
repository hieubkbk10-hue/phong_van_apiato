<?php

namespace App\Containers\AppSection\Order\Data\Factories;

use App\Containers\AppSection\Order\Models\Customer;
use App\Ship\Parents\Factories\Factory as ParentFactory;

class CustomerFactory extends ParentFactory
{
    /**
     * @var class-string<Customer>
     *
     * @psalm-suppress NonInvariantDocblockPropertyType
     */
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'address' => $this->faker->address(),
        ];
    }
}
