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
 */
class UpdateOrderTaskTest extends TestCase
{
    // TODO TEST
    public function testUpdateOrder(): void
    {
        $order = Order::factory()->create([
            // 'some_field' => 'new_field_value',
        ]);
        $data = [
            // 'some_field' => 'new_field_value',
        ];

        $updatedOrder = app(UpdateOrderTask::class)->run($data, $order->id);

        $this->assertEquals($order->id, $updatedOrder->id);
        // assert if fields are updated
        // $this->assertEquals($data['some_field'], $updatedOrder->some_field);
    }

    public function testUpdateOrderWithInvalidId(): void
    {
        $this->expectException(NotFoundException::class);

        $noneExistingId = 777777;

        app(UpdateOrderTask::class)->run([], $noneExistingId);
    }
}
