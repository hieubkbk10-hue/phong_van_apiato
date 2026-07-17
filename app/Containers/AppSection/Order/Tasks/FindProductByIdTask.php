<?php

namespace App\Containers\AppSection\Order\Tasks;

use App\Containers\AppSection\Order\Data\Repositories\ProductRepository;
use App\Containers\AppSection\Order\Models\Product;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;

class FindProductByIdTask extends ParentTask
{
    public function __construct(
        protected ProductRepository $repository
    ) {
    }

    public function run($id): Product
    {
        try {
            return $this->repository->find($id);
        } catch (Exception $exception) {
            throw new NotFoundException("Không tìm thấy sản phẩm yêu cầu.");
        }
    }
}
