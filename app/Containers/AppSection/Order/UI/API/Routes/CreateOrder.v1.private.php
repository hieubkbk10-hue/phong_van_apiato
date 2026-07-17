<?php

/**
 * @apiGroup           Order
 * @apiName            CreateOrder
 *
 * @api                {POST} /v1/orders Create Order
 * @apiDescription     Endpoint description here...
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiBody           {String} [customer_id] ID khách hàng cũ (nếu chọn khách hàng cũ)
 * @apiBody           {String} [customer_name] Tên khách hàng mới (bắt buộc nếu không có customer_id)
 * @apiBody           {String} [customer_phone] Số điện thoại khách hàng mới (bắt buộc nếu không có customer_id)
 * @apiBody           {String} [customer_address] Địa chỉ khách hàng mới (bắt buộc nếu không có customer_id)
 * @apiBody           {String} delivery_date Ngày giao hàng (định dạng YYYY-MM-DD)
 * @apiBody           {String} shipping_carrier Đơn vị vận chuyển
 * @apiBody           {String="COD","CASH","BANK_TRANSFER","DEBT"} payment_method Hình thức thanh toán
 * @apiBody           {Number} [debt_days] Số ngày nợ (nếu payment_method = DEBT)
 * @apiBody           {String} [bank_name] Tên ngân hàng nhận (nếu payment_method = BANK_TRANSFER)
 * @apiBody           {String} [bank_account] Số tài khoản ngân hàng nhận (nếu payment_method = BANK_TRANSFER)
 * @apiBody           {Number} [down_payment=0] Số tiền trả trước
 * @apiBody           {Number} [shipping_fee=0] Phí giao hàng
 * @apiBody           {Object[]} items Danh sách sản phẩm mua
 * @apiBody           {String} items.product_id ID của sản phẩm
 * @apiBody           {Number} items.price Giá bán sản phẩm lúc mua
 * @apiBody           {Number} items.quantity Số lượng sản phẩm
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "object": "Order",
 *         "id": "XyZ123",
 *         "order_code": "DH-20260717-0001",
 *         "delivery_date": "2026-07-20",
 *         "shipping_carrier": "Giao Hàng Nhanh",
 *         "payment_method": "COD",
 *         "status": "pending",
 *         "down_payment": 0,
 *         "shipping_fee": 30000
 *     }
 * }
 */

use App\Containers\AppSection\Order\UI\API\Controllers\CreateOrderController;
use Illuminate\Support\Facades\Route;

Route::post('orders', [CreateOrderController::class, 'createOrder'])
    ->middleware(['auth:api']);

