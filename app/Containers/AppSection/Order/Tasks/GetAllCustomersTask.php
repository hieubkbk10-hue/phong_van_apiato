<?php

namespace App\Containers\AppSection\Order\Tasks;

use App\Containers\AppSection\Order\Data\Repositories\CustomerRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

class GetAllCustomersTask extends ParentTask
{
    public function __construct(
        protected CustomerRepository $repository
    ) {
    }

    public function run(): mixed
    {
        return $this->addRequestCriteria()->repository->paginate();
    }
}
