<?php

namespace App\Containers\AppSection\Order\Tasks;

use App\Containers\AppSection\Order\Data\Repositories\ProductRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

class GetAllProductsTask extends ParentTask
{
    public function __construct(
        protected ProductRepository $repository
    ) {
    }

    public function run(): mixed
    {
        return $this->addRequestCriteria()->repository->paginate();
    }
}
