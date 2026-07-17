<?php

namespace App\Containers\AppSection\Order\UI\API\Controllers;

use App\Containers\AppSection\Order\Actions\DeleteCustomerAction;
use App\Containers\AppSection\Order\UI\API\Requests\DeleteCustomerRequest;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class DeleteCustomerController extends ApiController
{
    /**
     * @throws DeleteResourceFailedException
     * @throws NotFoundException
     */
    public function deleteCustomer(DeleteCustomerRequest $request): JsonResponse
    {
        app(DeleteCustomerAction::class)->run($request);

        return $this->noContent();
    }
}
