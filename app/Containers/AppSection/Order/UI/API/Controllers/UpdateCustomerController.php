<?php

namespace App\Containers\AppSection\Order\UI\API\Controllers;

use Apiato\Core\Exceptions\InvalidTransformerException;
use App\Containers\AppSection\Order\Actions\UpdateCustomerAction;
use App\Containers\AppSection\Order\UI\API\Requests\UpdateCustomerRequest;
use App\Containers\AppSection\Order\UI\API\Transformers\CustomerTransformer;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class UpdateCustomerController extends ApiController
{
    /**
     * @throws InvalidTransformerException
     * @throws NotFoundException
     * @throws UpdateResourceFailedException
     */
    public function updateCustomer(UpdateCustomerRequest $request): JsonResponse
    {
        $customer = app(UpdateCustomerAction::class)->run($request);

        return $this->accepted($this->transform($customer, CustomerTransformer::class));
    }
}
