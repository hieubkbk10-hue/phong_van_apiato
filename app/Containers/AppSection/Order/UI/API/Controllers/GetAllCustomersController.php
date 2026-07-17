<?php

namespace App\Containers\AppSection\Order\UI\API\Controllers;

use App\Containers\AppSection\Order\Actions\GetAllCustomersAction;
use App\Containers\AppSection\Order\UI\API\Requests\GetAllCustomersRequest;
use App\Containers\AppSection\Order\UI\API\Transformers\CustomerTransformer;
use App\Ship\Parents\Controllers\ApiController;

class GetAllCustomersController extends ApiController
{
    public function getAllCustomers(GetAllCustomersRequest $request): array
    {
        $customers = app(GetAllCustomersAction::class)->run($request);

        return $this->transform($customers, CustomerTransformer::class);
    }
}
