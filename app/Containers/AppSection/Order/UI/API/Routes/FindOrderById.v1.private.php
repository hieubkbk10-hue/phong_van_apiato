<?php

/**
 * @apiGroup           Order
 *
 * @apiName            FindOrderById
 *
 * @api                {GET} /v1/orders/:id Find Order By Id
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
 * @apiParam           {String} id ID của đơn hàng (HashID)
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

use App\Containers\AppSection\Order\UI\API\Controllers\FindOrderByIdController;
use Illuminate\Support\Facades\Route;

Route::get('orders/{id}', [FindOrderByIdController::class, 'findOrderById'])
    ->middleware(['auth:api']);
