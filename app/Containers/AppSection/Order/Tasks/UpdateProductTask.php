<?php

namespace App\Containers\AppSection\Order\Tasks;

use App\Containers\AppSection\Order\Data\Repositories\ProductRepository;
use App\Containers\AppSection\Order\Models\Product;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;

class UpdateProductTask extends ParentTask
{
    public function __construct(
        protected ProductRepository $repository
    ) {
    }

    public function run($id, array $data): Product
    {
        try {
            return $this->repository->update($data, $id);
        } catch (Exception $exception) {
            throw new Exception("Không thể cập nhật sản phẩm.");
        }
    }
}
