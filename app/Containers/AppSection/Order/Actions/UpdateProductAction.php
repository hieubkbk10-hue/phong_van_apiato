<?php

namespace App\Containers\AppSection\Order\Actions;

use App\Containers\AppSection\Order\Models\Product;
use App\Containers\AppSection\Order\Tasks\UpdateProductTask;
use App\Containers\AppSection\Order\UI\API\Requests\UpdateProductRequest;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Actions\Action as ParentAction;

class UpdateProductAction extends ParentAction
{
    /**
     * @throws NotFoundException
     * @throws UpdateResourceFailedException
     */
    public function run(UpdateProductRequest $request): Product
    {
        $data = $request->sanitizeInput([
            'name',
            'price',
            'stock',
        ]);

        return app(UpdateProductTask::class)->run($request->id, $data);
    }
}
