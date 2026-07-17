<?php

namespace App\Containers\AppSection\Order\Tests\Unit;

use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Order\Tasks\GetAllOrdersTask;
use App\Containers\AppSection\Order\Tests\TestCase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class GetAllOrdersTaskTest.
 *
 * @group order
 * @group unit
 */
class GetAllOrdersTaskTest extends TestCase
{
    public function testGetAllOrders(): void
    {
        Order::factory()->count(3)->create();

        $foundOrders = app(GetAllOrdersTask::class)->run();

        $this->assertCount(3, $foundOrders);
        $this->assertInstanceOf(LengthAwarePaginator::class, $foundOrders);
    }
}
