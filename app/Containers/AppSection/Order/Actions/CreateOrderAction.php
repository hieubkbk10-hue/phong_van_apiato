<?php

namespace App\Containers\AppSection\Order\Actions;

use Apiato\Core\Exceptions\IncorrectIdException;
use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Order\Tasks\CreateCustomerTask;
use App\Containers\AppSection\Order\Tasks\CreateOrderItemTask;
use App\Containers\AppSection\Order\Tasks\CreateOrderTask;
use App\Containers\AppSection\Order\Tasks\FindProductByIdTask;
use App\Containers\AppSection\Order\Tasks\UpdateProductTask;
use App\Containers\AppSection\Order\UI\API\Requests\CreateOrderRequest;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateOrderAction extends ParentAction
{
    /**
     * @throws CreateResourceFailedException
     * @throws IncorrectIdException
     */
    public function run(CreateOrderRequest $request): Order
    {
        $data = $request->sanitizeInput([
            'customer_id',
            'customer_name',
            'customer_phone',
            'customer_address',
            'delivery_date',
            'shipping_carrier',
            'payment_method',
            'debt_days',
            'bank_name',
            'bank_account',
            'down_payment',
            'shipping_fee',
            'items',
        ]);

        return DB::transaction(function () use ($data): Order {
            $customerId = $data['customer_id'] ?? null;
            if (! $customerId) {
                /** @var CreateCustomerTask $createCustomerTask */
                $createCustomerTask = app(CreateCustomerTask::class);
                $customer = $createCustomerTask->run([
                    'name' => $data['customer_name'],
                    'phone' => $data['customer_phone'],
                    'address' => $data['customer_address'],
                ]);
                $customerId = $customer->id;
            }

            $orderCode = 'ORD-'.strtoupper(Str::random(10));

            $orderData = [
                'order_code' => $orderCode,
                'customer_id' => $customerId,
                'delivery_date' => $data['delivery_date'],
                'shipping_carrier' => $data['shipping_carrier'],
                'payment_method' => $data['payment_method'],
                'debt_days' => $data['debt_days'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
                'bank_account' => $data['bank_account'] ?? null,
                'down_payment' => $data['down_payment'] ?? 0.0,
                'shipping_fee' => $data['shipping_fee'] ?? 0.0,
                'status' => 'pending',
            ];

            /** @var CreateOrderTask $createOrderTask */
            $createOrderTask = app(CreateOrderTask::class);
            $order = $createOrderTask->run($orderData);

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    /** @var FindProductByIdTask $findProductByIdTask */
                    $findProductByIdTask = app(FindProductByIdTask::class);
                    $product = $findProductByIdTask->run($item['product_id']);

                    if ($product->stock < $item['quantity']) {
                        throw new CreateResourceFailedException("Sản phẩm {$product->name} không đủ tồn kho.");
                    }

                    // Trừ stock sản phẩm
                    /** @var UpdateProductTask $updateProductTask */
                    $updateProductTask = app(UpdateProductTask::class);
                    $updateProductTask->run($product->id, [
                        'stock' => $product->stock - $item['quantity'],
                    ]);

                    // Tạo order item
                    /** @var CreateOrderItemTask $createOrderItemTask */
                    $createOrderItemTask = app(CreateOrderItemTask::class);
                    $createOrderItemTask->run([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }

            return $order;
        });
    }
}
