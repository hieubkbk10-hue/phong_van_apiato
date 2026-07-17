<?php

namespace App\Containers\AppSection\Order\Actions;

use App\Containers\AppSection\Order\Models\Customer;
use App\Containers\AppSection\Order\Tasks\CreateCustomerTask;
use App\Containers\AppSection\Order\UI\API\Requests\CreateCustomerRequest;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Actions\Action as ParentAction;

class CreateCustomerAction extends ParentAction
{
    /**
     * @throws CreateResourceFailedException
     */
    public function run(CreateCustomerRequest $request): Customer
    {
        $data = $request->sanitizeInput([
            'name',
            'phone',
            'address',
        ]);

        return app(CreateCustomerTask::class)->run($data);
    }
}
