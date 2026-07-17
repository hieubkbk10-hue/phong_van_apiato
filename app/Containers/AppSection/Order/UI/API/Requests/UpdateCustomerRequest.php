<?php

namespace App\Containers\AppSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class UpdateCustomerRequest extends ParentRequest
{
    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    protected array $decode = [
        'id',
    ];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        return [
            'name'    => 'sometimes|string|max:255',
            'phone'   => 'sometimes|string|max:15|unique:customers,phone,' . $this->id,
            'address' => 'sometimes|string|max:500',
        ];
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess',
        ]);
    }
}
