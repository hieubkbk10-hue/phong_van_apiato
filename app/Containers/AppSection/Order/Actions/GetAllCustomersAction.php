<?php

namespace App\Containers\AppSection\Order\Actions;

use App\Containers\AppSection\Order\Tasks\GetAllCustomersTask;
use App\Containers\AppSection\Order\UI\API\Requests\GetAllCustomersRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

class GetAllCustomersAction extends ParentAction
{
    public function run(GetAllCustomersRequest $request): mixed
    {
        return app(GetAllCustomersTask::class)->run();
    }
}
