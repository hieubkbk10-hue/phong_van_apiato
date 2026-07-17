<?php

namespace App\Containers\AppSection\Order\Tests\Unit;

use App\Containers\AppSection\Order\Tests\TestCase;
use Illuminate\Support\Facades\Schema;

/**
 * Class OrdersMigrationTest.
 *
 * @group order
 * @group unit
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class OrdersMigrationTest extends TestCase
{
    public function test_orders_table_has_expected_columns(): void
    {
        $columns = [
            'id',
            'order_code',
            'delivery_date',
            'shipping_carrier',
            'payment_method',
            'debt_days',
            'bank_name',
            'bank_account',
            'down_payment',
            'shipping_fee',
            'status',
            'customer_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('orders', $column));
        }
    }
}
