<?php

namespace App\Containers\AppSection\Order\Actions;

use App\Containers\AppSection\Order\Models\Product;
use App\Containers\AppSection\Order\Tasks\CreateProductTask;
use App\Containers\AppSection\Order\UI\API\Requests\CreateProductRequest;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Actions\Action as ParentAction;

class CreateProductAction extends ParentAction
{
    /**
     * @throws CreateResourceFailedException
     */
    public function run(CreateProductRequest $request): Product
    {
        $data = $request->sanitizeInput([
            'name',
            'price',
            'stock',
        ]);

        return app(CreateProductTask::class)->run($data);
    }
}
