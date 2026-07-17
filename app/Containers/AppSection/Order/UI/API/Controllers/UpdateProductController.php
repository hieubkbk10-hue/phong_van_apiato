<?php

namespace App\Containers\AppSection\Order\UI\API\Controllers;

use Apiato\Core\Exceptions\InvalidTransformerException;
use App\Containers\AppSection\Order\Actions\UpdateProductAction;
use App\Containers\AppSection\Order\UI\API\Requests\UpdateProductRequest;
use App\Containers\AppSection\Order\UI\API\Transformers\ProductTransformer;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class UpdateProductController extends ApiController
{
    /**
     * @throws InvalidTransformerException
     * @throws NotFoundException
     * @throws UpdateResourceFailedException
     */
    public function updateProduct(UpdateProductRequest $request): JsonResponse
    {
        $product = app(UpdateProductAction::class)->run($request);

        return $this->accepted($this->transform($product, ProductTransformer::class));
    }
}
