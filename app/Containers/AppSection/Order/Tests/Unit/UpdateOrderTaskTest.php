<?php

namespace App\Containers\AppSection\Order\Tests\Unit;

use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Order\Tasks\UpdateOrderTask;
use App\Containers\AppSection\Order\Tests\TestCase;
use App\Ship\Exceptions\NotFoundException;

/**
 * Class UpdateOrderTaskTest.
 *
 * @group order
 * @group unit
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class UpdateOrderTaskTest extends TestCase
{
    public function test_update_order(): void
    {
        /** @var Order $order */
        $order = Order::factory()->create([
            'shipping_carrier' => 'GHN',
        ]);
        $data = [
            'shipping_carrier' => 'GHTK',
        ];

        /** @var UpdateOrderTask $updateOrderTask */
        $updateOrderTask = app(UpdateOrderTask::class);
        $updatedOrder = $updateOrderTask->run($data, $order->id);

        $this->assertEquals($order->id, $updatedOrder->id);
        $this->assertEquals('GHTK', $updatedOrder->shipping_carrier);
    }

    public function test_update_order_with_invalid_id(): void
    {
        $this->expectException(NotFoundException::class);

        $noneExistingId = 777777;

        /** @var UpdateOrderTask $updateOrderTask */
        $updateOrderTask = app(UpdateOrderTask::class);
        $updateOrderTask->run([], $noneExistingId);
    }
}
