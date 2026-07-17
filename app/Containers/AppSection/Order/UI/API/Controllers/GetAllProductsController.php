<?php

namespace App\Containers\AppSection\Order\UI\API\Controllers;

use App\Containers\AppSection\Order\Actions\GetAllProductsAction;
use App\Containers\AppSection\Order\UI\API\Requests\GetAllProductsRequest;
use App\Containers\AppSection\Order\UI\API\Transformers\ProductTransformer;
use App\Ship\Parents\Controllers\ApiController;

class GetAllProductsController extends ApiController
{
    public function getAllProducts(GetAllProductsRequest $request): array
    {
        $products = app(GetAllProductsAction::class)->run($request);

        return $this->transform($products, ProductTransformer::class);
    }
}
