<?php

namespace App\Containers\AppSection\Order\Actions;

use App\Containers\AppSection\Order\Tasks\GetAllProductsTask;
use App\Containers\AppSection\Order\UI\API\Requests\GetAllProductsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

class GetAllProductsAction extends ParentAction
{
    public function run(GetAllProductsRequest $request): mixed
    {
        return app(GetAllProductsTask::class)->run();
    }
}
