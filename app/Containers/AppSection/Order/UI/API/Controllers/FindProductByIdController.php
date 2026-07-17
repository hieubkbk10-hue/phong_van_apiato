<?php

namespace App\Containers\AppSection\Order\UI\API\Controllers;

use Apiato\Core\Exceptions\InvalidTransformerException;
use App\Containers\AppSection\Order\Actions\FindProductByIdAction;
use App\Containers\AppSection\Order\UI\API\Requests\FindProductByIdRequest;
use App\Containers\AppSection\Order\UI\API\Transformers\ProductTransformer;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Controllers\ApiController;

class FindProductByIdController extends ApiController
{
    /**
     * @throws InvalidTransformerException
     * @throws NotFoundException
     */
    public function findProductById(FindProductByIdRequest $request): array
    {
        $product = app(FindProductByIdAction::class)->run($request);

        return $this->transform($product, ProductTransformer::class);
    }
}
