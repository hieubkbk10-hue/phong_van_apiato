<?php

namespace App\Containers\AppSection\Order\Tests\Unit;

use App\Containers\AppSection\Order\Models\Customer;
use App\Containers\AppSection\Order\Tasks\CreateOrderTask;
use App\Containers\AppSection\Order\Tests\TestCase;
use App\Ship\Exceptions\CreateResourceFailedException;

/**
 * Class CreateOrderTaskTest.
 *
 * @group order
 * @group unit
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CreateOrderTaskTest extends TestCase
{
    public function test_create_order(): void
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();
        $data = [
            'order_code' => 'ORD-1234567890',
            'customer_id' => $customer->id,
            'delivery_date' => now()->addDays(2)->format('Y-m-d'),
            'shipping_carrier' => 'GHN',
            'payment_method' => 'COD',
            'status' => 'pending',
        ];

        /** @var CreateOrderTask $createOrderTask */
        $createOrderTask = app(CreateOrderTask::class);
        $order = $createOrderTask->run($data);

        $this->assertModelExists($order);
        $this->assertEquals('ORD-1234567890', $order->order_code);
    }

    // TODO TEST
    //    public function testCreateOrderWithInvalidData(): void
    //    {
    //        $this->expectException(CreateResourceFailedException::class);
    //
    //        $data = [
    //            // put some invalid data here
    //            // 'invalid' => 'data',
    //        ];
    //
    //        app(CreateOrderTask::class)->run($data);
    //    }
}
