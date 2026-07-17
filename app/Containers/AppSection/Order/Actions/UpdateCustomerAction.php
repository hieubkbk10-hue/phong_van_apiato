<?php

namespace App\Containers\AppSection\Order\Actions;

use App\Containers\AppSection\Order\Models\Customer;
use App\Containers\AppSection\Order\Tasks\UpdateCustomerTask;
use App\Containers\AppSection\Order\UI\API\Requests\UpdateCustomerRequest;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Actions\Action as ParentAction;

class UpdateCustomerAction extends ParentAction
{
    /**
     * @throws NotFoundException
     * @throws UpdateResourceFailedException
     */
    public function run(UpdateCustomerRequest $request): Customer
    {
        $data = $request->sanitizeInput([
            'name',
            'phone',
            'address',
        ]);

        return app(UpdateCustomerTask::class)->run($data, $request->id);
    }
}
