<?php

namespace App\Containers\AppSection\Order\Tests\Unit;

use App\Containers\AppSection\Order\Tasks\CreateOrderTask;
use App\Containers\AppSection\Order\Tests\TestCase;
use App\Ship\Exceptions\CreateResourceFailedException;

/**
 * Class CreateOrderTaskTest.
 *
 * @group order
 * @group unit
 */
class CreateOrderTaskTest extends TestCase
{
    public function testCreateOrder(): void
    {
        $data = [];

        $order = app(CreateOrderTask::class)->run($data);

        $this->assertModelExists($order);
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
