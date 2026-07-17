<?php

namespace App\Containers\AppSection\Order\Data\Factories;

use App\Containers\AppSection\Order\Models\Product;
use App\Ship\Parents\Factories\Factory as ParentFactory;

class ProductFactory extends ParentFactory
{
    /**
     * @var class-string<Product>
     *
     * @psalm-suppress NonInvariantDocblockPropertyType
     */
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->randomFloat(2, 10000, 1000000),
            'stock' => $this->faker->numberBetween(10, 100),
        ];
    }
}
