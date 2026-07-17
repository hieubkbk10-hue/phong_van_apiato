<?php

namespace App\Containers\AppSection\Order\Tasks;

use App\Containers\AppSection\Order\Data\Repositories\CustomerRepository;
use App\Containers\AppSection\Order\Models\Customer;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;

class FindCustomerByIdTask extends ParentTask
{
    public function __construct(
        protected CustomerRepository $repository
    ) {
    }

    public function run($id): Customer
    {
        try {
            return $this->repository->find($id);
        } catch (Exception $exception) {
            throw new NotFoundException("Không tìm thấy khách hàng yêu cầu.");
        }
    }
}
