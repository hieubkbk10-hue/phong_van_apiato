<?php

namespace App\Containers\AppSection\Order\Tests\Unit;

use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Order\Tasks\FindOrderByIdTask;
use App\Containers\AppSection\Order\Tests\TestCase;
use App\Ship\Exceptions\NotFoundException;

/**
 * Class FindOrderByIdTaskTest.
 *
 * @group order
 * @group unit
 */
class FindOrderByIdTaskTest extends TestCase
{
    public function testFindOrderById(): void
    {
        $order = Order::factory()->create();

        $foundOrder = app(FindOrderByIdTask::class)->run($order->id);

        $this->assertEquals($order->id, $foundOrder->id);
    }

    public function testFindOrderWithInvalidId(): void
    {
        $this->expectException(NotFoundException::class);

        $noneExistingId = 777777;

        app(FindOrderByIdTask::class)->run($noneExistingId);
    }
}
