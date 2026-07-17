<?php

namespace App\Containers\AppSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class CreateProductRequest extends ParentRequest
{
    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    protected array $decode = [
        //
    ];

    protected array $urlParameters = [
        //
    ];

    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:255|unique:products,name',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess',
        ]);
    }
}
