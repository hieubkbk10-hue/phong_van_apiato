<?php

namespace App\Containers\AppSection\Order\Tests\Unit;

use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Order\Tasks\DeleteOrderTask;
use App\Containers\AppSection\Order\Tests\TestCase;
use App\Ship\Exceptions\NotFoundException;

/**
 * Class DeleteOrderTaskTest.
 *
 * @group order
 * @group unit
 */
class DeleteOrderTaskTest extends TestCase
{
    public function testDeleteOrder(): void
    {
        $order = Order::factory()->create();

        $result = app(DeleteOrderTask::class)->run($order->id);

        $this->assertEquals(1, $result);
    }

    public function testDeleteOrderWithInvalidId(): void
    {
        $this->expectException(NotFoundException::class);

        $noneExistingId = 777777;

        app(DeleteOrderTask::class)->run($noneExistingId);
    }
}
