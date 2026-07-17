<?php

/**
 * @apiGroup           Order
 *
 * @apiName            UpdateOrder
 *
 * @api                {PATCH} /v1/orders/:id Update Order
 *
 * @apiDescription     Endpoint description here...
 *
 * @apiVersion         1.0.0
 *
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id ID của đơn hàng cần cập nhật (HashID)
 *
 * @apiBody           {String} [customer_id] ID khách hàng cũ
 * @apiBody           {String} [delivery_date] Ngày giao hàng (YYYY-MM-DD)
 * @apiBody           {String} [shipping_carrier] Đơn vị vận chuyển
 * @apiBody           {String="COD","CASH","BANK_TRANSFER","DEBT"} [payment_method] Hình thức thanh toán
 * @apiBody           {Number} [debt_days] Số ngày nợ (nếu payment_method = DEBT)
 * @apiBody           {String} [bank_name] Tên ngân hàng (nếu payment_method = BANK_TRANSFER)
 * @apiBody           {String} [bank_account] Số tài khoản ngân hàng (nếu payment_method = BANK_TRANSFER)
 * @apiBody           {Number} [down_payment] Số tiền trả trước
 * @apiBody           {Number} [shipping_fee] Phí giao hàng
 * @apiBody           {Object[]} [items] Danh sách sản phẩm mua mới (thay thế danh sách cũ)
 * @apiBody           {String} items.product_id ID sản phẩm
 * @apiBody           {Number} items.price Giá bán lúc mua
 * @apiBody           {Number} items.quantity Số lượng
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
 *         "down_payment": 100000,
 *         "shipping_fee": 30000
 *     }
 * }
 */

use App\Containers\AppSection\Order\UI\API\Controllers\UpdateOrderController;
use Illuminate\Support\Facades\Route;

Route::patch('orders/{id}', [UpdateOrderController::class, 'updateOrder'])
    ->middleware(['auth:api']);
