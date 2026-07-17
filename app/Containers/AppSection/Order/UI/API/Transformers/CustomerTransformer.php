<?php

namespace App\Containers\AppSection\Order\UI\API\Transformers;

use App\Containers\AppSection\Order\Models\Customer;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class CustomerTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
        //
    ];

    protected array $availableIncludes = [
        //
    ];

    public function transform(Customer $customer): array
    {
        $response = [
            'object' => $customer->getResourceKey(),
            'id' => $customer->getHashedKey(), // Mã hóa ID của khách hàng
            'name' => $customer->name,
            'phone' => $customer->phone,
            'address' => $customer->address,
        ];

        return $this->ifAdmin([
            'real_id' => $customer->id,
            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at,
        ], $response);
    }
}
