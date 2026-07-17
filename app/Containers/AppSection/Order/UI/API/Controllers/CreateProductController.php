<?php

namespace App\Containers\AppSection\Order\UI\API\Controllers;

use Apiato\Core\Exceptions\InvalidTransformerException;
use App\Containers\AppSection\Order\Actions\CreateProductAction;
use App\Containers\AppSection\Order\UI\API\Requests\CreateProductRequest;
use App\Containers\AppSection\Order\UI\API\Transformers\ProductTransformer;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class CreateProductController extends ApiController
{
    /**
     * @throws CreateResourceFailedException
     * @throws InvalidTransformerException
     */
    public function createProduct(CreateProductRequest $request): JsonResponse
    {
        $product = app(CreateProductAction::class)->run($request);

        return $this->created($this->transform($product, ProductTransformer::class));
    }
}
