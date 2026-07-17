<?php

namespace App\Containers\AppSection\Order\UI\API\Requests;

use App\Containers\AppSection\Order\Models\Order;
use App\Ship\Parents\Requests\Request as ParentRequest;

class CreateOrderRequest extends ParentRequest
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
        'customer_id',
        'items.*.product_id',
    ];

    /**
     * Defining the URL parameters (e.g, `/user/{id}`) allows applying
     * validation rules on them and allows accessing them like request data.
     */
    protected array $urlParameters = [
        // 'id',
    ];

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Khách hàng
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'customer_phone' => 'required_without:customer_id|string|max:15|unique:customers,phone',
            'customer_address' => 'required_without:customer_id|string|max:500',

            // Đơn hàng
            'delivery_date' => 'required|date|after_or_equal:today',
            'shipping_carrier' => 'required|string|max:255',
            'payment_method' => 'required|string|in:'.implode(',', [Order::PAYMENT_COD, Order::PAYMENT_CASH, Order::PAYMENT_BANK_TRANSFER, Order::PAYMENT_DEBT]),

            // Validate có điều kiện cho hình thức thanh toán
            'debt_days' => 'required_if:payment_method,'.Order::PAYMENT_DEBT.'|integer|min:1',
            'bank_name' => 'required_if:payment_method,'.Order::PAYMENT_BANK_TRANSFER.'|string|max:255',
            'bank_account' => 'required_if:payment_method,'.Order::PAYMENT_BANK_TRANSFER.'|string|max:255',

            // Tiền bạc
            'down_payment' => 'numeric|min:0',
            'shipping_fee' => 'numeric|min:0',

            // Chi tiết sản phẩm
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
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
