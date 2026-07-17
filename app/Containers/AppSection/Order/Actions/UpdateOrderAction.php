<?php

namespace App\Containers\AppSection\Order\Actions;

use Apiato\Core\Exceptions\IncorrectIdException;
use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Order\Tasks\CreateOrderItemTask;
use App\Containers\AppSection\Order\Tasks\FindOrderByIdTask;
use App\Containers\AppSection\Order\Tasks\FindProductByIdTask;
use App\Containers\AppSection\Order\Tasks\UpdateOrderTask;
use App\Containers\AppSection\Order\Tasks\UpdateProductTask;
use App\Containers\AppSection\Order\UI\API\Requests\UpdateOrderRequest;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Actions\Action as ParentAction;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateOrderAction extends ParentAction
{
    /**
     * @throws UpdateResourceFailedException
     * @throws IncorrectIdException
     * @throws NotFoundException
     */
    public function run(UpdateOrderRequest $request): Order
    {
        $data = $request->sanitizeInput([
            'customer_id',
            'delivery_date',
            'shipping_carrier',
            'payment_method',
            'debt_days',
            'bank_name',
            'bank_account',
            'down_payment',
            'shipping_fee',
            'status',
            'items',
        ]);

        /** @var FindOrderByIdTask $findOrderByIdTask */
        $findOrderByIdTask = app(FindOrderByIdTask::class);
        $order = $findOrderByIdTask->run($request->id);

        return DB::transaction(function () use ($order, $data): Order {
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($order->items as $oldItem) {
                    if ($oldItem->product_id) {
                        try {
                            /** @var FindProductByIdTask $findProductByIdTask */
                            $findProductByIdTask = app(FindProductByIdTask::class);
                            $product = $findProductByIdTask->run($oldItem->product_id);

                            /** @var UpdateProductTask $updateProductTask */
                            $updateProductTask = app(UpdateProductTask::class);
                            $updateProductTask->run($product->id, [
                                'stock' => $product->stock + $oldItem->quantity,
                            ]);
                        } catch (Exception) {
                            // Bỏ qua nếu không tìm thấy product cũ để refund
                        }
                    }
                }

                $order->items()->delete();

                foreach ($data['items'] as $item) {
                    /** @var FindProductByIdTask $findProductByIdTask */
                    $findProductByIdTask = app(FindProductByIdTask::class);
                    $product = $findProductByIdTask->run($item['product_id']);

                    if ($product->stock < $item['quantity']) {
                        throw new UpdateResourceFailedException("Sản phẩm {$product->name} không đủ tồn kho.");
                    }

                    /** @var UpdateProductTask $updateProductTask */
                    $updateProductTask = app(UpdateProductTask::class);
                    $updateProductTask->run($product->id, [
                        'stock' => $product->stock - $item['quantity'],
                    ]);

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

            $orderData = collect($data)->except(['items'])->toArray();

            /** @var UpdateOrderTask $updateOrderTask */
            $updateOrderTask = app(UpdateOrderTask::class);

            return $updateOrderTask->run($orderData, $order->id);
        });
    }
}
