<?php

namespace App\Containers\AppSection\Order\Tasks;

use App\Containers\AppSection\Order\Data\Repositories\CustomerRepository;
use App\Containers\AppSection\Order\Models\Customer;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;

class CreateCustomerTask extends ParentTask
{
    public function __construct(
        protected CustomerRepository $repository
    ) {
    }

    public function run(array $data): Customer
    {
        try {
            return $this->repository->create($data);
        } catch (Exception $exception) {
            throw new Exception("Không thể tạo khách hàng mới.");
        }
    }
}
