<?php

namespace App\Containers\AppSection\Order\Actions;

use App\Containers\AppSection\Order\Tasks\DeleteProductTask;
use App\Containers\AppSection\Order\UI\API\Requests\DeleteProductRequest;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Actions\Action as ParentAction;

class DeleteProductAction extends ParentAction
{
    /**
     * @throws NotFoundException
     * @throws DeleteResourceFailedException
     */
    public function run(DeleteProductRequest $request): int
    {
        return app(DeleteProductTask::class)->run($request->id);
    }
}
