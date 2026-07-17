<?php

namespace App\Containers\AppSection\Order\UI\API\Transformers;

use App\Containers\AppSection\Order\Models\Product;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class ProductTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
        //
    ];

    protected array $availableIncludes = [
        //
    ];

    public function transform(Product $product): array
    {
        $response = [
            'object' => $product->getResourceKey(),
            'id'     => $product->getHashedKey(), // Mã hóa ID của sản phẩm
            'name'   => $product->name,
            'price'  => $product->price,
            'stock'  => $product->stock,
        ];

        return $this->ifAdmin([
            'real_id'    => $product->id,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ], $response);
    }
}
