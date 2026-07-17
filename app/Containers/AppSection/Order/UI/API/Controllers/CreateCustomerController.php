<?php

namespace App\Containers\AppSection\Order\UI\API\Controllers;

use Apiato\Core\Exceptions\InvalidTransformerException;
use App\Containers\AppSection\Order\Actions\CreateCustomerAction;
use App\Containers\AppSection\Order\UI\API\Requests\CreateCustomerRequest;
use App\Containers\AppSection\Order\UI\API\Transformers\CustomerTransformer;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class CreateCustomerController extends ApiController
{
    /**
     * @throws CreateResourceFailedException
     * @throws InvalidTransformerException
     */
    public function createCustomer(CreateCustomerRequest $request): JsonResponse
    {
        $customer = app(CreateCustomerAction::class)->run($request);

        return $this->created($this->transform($customer, CustomerTransformer::class));
    }
}
