<?php

namespace App\Containers\AppSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class UpdateOrderRequest extends ParentRequest
{
    /**
     * Define which Roles and/or Permissions has access to this request.
     */
    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     */
    protected array $decode = [
        'id',
        'customer_id',
        'items.*.product_id',
    ];

    /**
     * Defining the URL parameters (e.g, `/user/{id}`) allows applying
     * validation rules on them and allows accessing them like request data.
     */
    protected array $urlParameters = [
        'id',
    ];

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Khách hàng
            'customer_id'       => 'sometimes|nullable|exists:customers,id',

            // Đơn hàng
            'delivery_date'     => 'sometimes|date',
            'shipping_carrier'  => 'sometimes|string|max:255',
            'payment_method'    => 'sometimes|string|in:COD,CASH,BANK_TRANSFER,DEBT',
            
            // Validate có điều kiện cho hình thức thanh toán
            'debt_days'         => 'sometimes|required_if:payment_method,DEBT|integer|min:1',
            'bank_name'         => 'sometimes|required_if:payment_method,BANK_TRANSFER|string|max:255',
            'bank_account'      => 'sometimes|required_if:payment_method,BANK_TRANSFER|string|max:255',

            // Tiền bạc
            'down_payment'      => 'sometimes|numeric|min:0',
            'shipping_fee'      => 'sometimes|numeric|min:0',

            // Chi tiết sản phẩm (nếu gửi kèm)
            'items'              => 'sometimes|array|min:1',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.price'      => 'required_with:items|numeric|min:0',
            'items.*.quantity'   => 'required_with:items|integer|min:1',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->check([
            'hasAccess',
        ]);
    }
}
