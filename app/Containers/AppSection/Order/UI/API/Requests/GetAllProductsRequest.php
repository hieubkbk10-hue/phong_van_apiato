<?php

namespace App\Containers\AppSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class GetAllProductsRequest extends ParentRequest
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
            'limit' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'search' => 'nullable|string',
            'orderBy' => 'nullable|string',
            'sortedBy' => 'nullable|in:asc,desc',
        ];
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess',
        ]);
    }
}
