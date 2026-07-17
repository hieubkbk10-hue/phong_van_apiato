<?php

/**
 * @apiGroup           Order
 *
 * @apiName            GetAllOrders
 *
 * @api                {GET} /v1/orders Get All Orders
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
 * @apiParam           {Number} [limit] Số lượng bản ghi mỗi trang (mặc định: 15)
 * @apiParam           {Number} [page] Trang hiện tại
 * @apiParam           {String} [search] Tìm kiếm (ví dụ: `order_code:DH-2026` hoặc `payment_method:COD`)
 * @apiParam           {String} [orderBy] Sắp xếp theo cột (ví dụ: `delivery_date`)
 * @apiParam           {String} [sortedBy] Hướng sắp xếp (`asc` hoặc `desc`)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": [
 *         {
 *             "object": "Order",
 *             "id": "XyZ123",
 *             "order_code": "DH-20260717-0001",
 *             "delivery_date": "2026-07-20",
 *             "shipping_carrier": "Giao Hàng Nhanh",
 *             "payment_method": "COD",
 *             "status": "pending",
 *             "down_payment": 0,
 *             "shipping_fee": 30000
 *         }
 *     ],
 *     "meta": {
 *         "pagination": {
 *             "total": 1,
 *             "count": 1,
 *             "per_page": 15,
 *             "current_page": 1,
 *             "total_pages": 1
 *         }
 *     }
 * }
 */

use App\Containers\AppSection\Order\UI\API\Controllers\GetAllOrdersController;
use Illuminate\Support\Facades\Route;

Route::get('orders', [GetAllOrdersController::class, 'getAllOrders'])
    ->middleware(['auth:api']);
