<?php

namespace App\Containers\AppSection\Order\Tasks;

use App\Containers\AppSection\Order\Data\Repositories\OrderItemRepository;
use App\Containers\AppSection\Order\Models\OrderItem;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;

class CreateOrderItemTask extends ParentTask
{
    public function __construct(
        protected OrderItemRepository $repository
    ) {
    }

    public function run(array $data): OrderItem
    {
        try {
            return $this->repository->create($data);
        } catch (Exception $exception) {
            throw new Exception("Không thể lưu chi tiết sản phẩm đơn hàng.");
        }
    }
}
