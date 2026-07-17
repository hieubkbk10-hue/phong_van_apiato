<?php

namespace App\Containers\AppSection\Order\UI\API\Tests\Functional;

use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Order\UI\API\Tests\ApiTestCase;
use Illuminate\Testing\Fluent\AssertableJson;

/**
 * Class FindOrderByIdTest.
 *
 * @group order
 * @group api
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FindOrderByIdTest extends ApiTestCase
{
    protected string $endpoint = 'get@v1/orders/{id}';

    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    public function test_find_order(): void
    {
        /** @var Order $order */
        $order = Order::factory()->create();

        $response = $this->injectId($order->id)->makeCall();

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('data')
                ->where('data.id', $order->getHashedKey())
                ->etc()
        );
    }

    public function test_find_non_existing_order(): void
    {
        $invalidId = 7777;

        $response = $this->injectId($invalidId)->makeCall([]);

        $response->assertStatus(404);
    }

    public function test_find_filtered_order_response(): void
    {
        /** @var Order $order */
        $order = Order::factory()->create();

        $response = $this->injectId($order->id)->endpoint($this->endpoint.'?filter=id')->makeCall();

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('data')
                ->where('data.id', $order->getHashedKey())
                ->missing('data.object')
        );
    }

    // TODO TEST
    // if your model have relationships which can be included into the response then
    // uncomment this test
    // modify it to your needs
    // test the relation
    //    public function testFindOrderWithRelation(): void
    //    {
    //        $order = Order::factory()->create();
    //        $relation = 'roles';
    //
    //        $response = $this->injectId($order->id)->endpoint($this->endpoint . "?include=$relation")->makeCall();
    //
    //        $response->assertStatus(200);
    //        $response->assertJson(
    //            fn (AssertableJson $json) =>
    //              $json->has('data')
    //                  ->where('data.id', $order->getHashedKey())
    //                  ->count("data.$relation.data", 1)
    //                  ->where("data.$relation.data.0.name", 'something')
    //                  ->etc()
    //        );
    //    }
}
