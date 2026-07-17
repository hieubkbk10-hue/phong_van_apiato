<?php

namespace App\Containers\AppSection\Order\UI\API\Tests\Functional;

use App\Containers\AppSection\Order\Models\Customer;
use App\Containers\AppSection\Order\Models\Product;
use App\Containers\AppSection\Order\UI\API\Tests\ApiTestCase;
use Illuminate\Testing\Fluent\AssertableJson;

/**
 * Class CreateOrderTest.
 *
 * @group order
 * @group api
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CreateOrderTest extends ApiTestCase
{
    protected string $endpoint = 'post@v1/orders';

    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    public function test_create_order(): void
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();
        /** @var Product $product */
        $product = Product::factory()->create(['price' => 100000, 'stock' => 10]);

        $data = [
            'customer_id' => $customer->getHashedKey(),
            'delivery_date' => now()->addDays(2)->format('Y-m-d'),
            'shipping_carrier' => 'GHTK',
            'payment_method' => 'COD',
            'items' => [
                [
                    'product_id' => $product->getHashedKey(),
                    'price' => 100000,
                    'quantity' => 2,
                ],
            ],
        ];

        $response = $this->makeCall($data);

        $response->assertStatus(201);
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('data')
                ->where('data.object', 'Order')
                ->where('data.shipping_carrier', 'GHTK')
                ->etc()
        );
    }

    // TODO TEST
    //    public function testCreateOrderWithInvalidFields(): void
    //    {
    //        $data = [
    //            // add some invalid field data here
    //            // 'something' => 'invalid',
    //        ];
    //
    //        $response = $this->makeCall($data);
    //
    //        $response->assertStatus(422);
    //        // validate errors and their messages here
    //        // $response->assertJson(
    //        //     fn (AssertableJson $json) =>
    //        //        $json->has('message')
    //        //            ->has('errors')
    //        //            ->where('errors.something.0', 'Some validation error message.')
    //        // );
    //    }

    // TODO TEST
    // add some roles and permissions to this route's request
    // then add them to the $access array above
    // uncomment this test to test accesses
    //    public function testGivenHaveNoAccess_CannotCreateOrder(): void
    //    {
    //        $this->getTestingUserWithoutAccess();
    //
    //        $response = $this->makeCall([]);
    //
    //        $response->assertStatus(403);
    //        $response->assertJson(
    //            fn (AssertableJson $json) =>
    //                $json->has('message')
    //                    ->where('message', 'This action is unauthorized.')
    //                    ->etc()
    //        );
    //    }
}
