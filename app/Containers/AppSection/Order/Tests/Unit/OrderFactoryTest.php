<?php

namespace App\Containers\AppSection\Order\Tests\Unit;

use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Order\Tests\TestCase;

/**
 * Class OrderFactoryTest.
 *
 * @group order
 * @group unit
 */
class OrderFactoryTest extends TestCase
{
    public function testCreateOrder(): void
    {
        $order = Order::factory()->make();

        $this->assertInstanceOf(Order::class, $order);
    }
}
