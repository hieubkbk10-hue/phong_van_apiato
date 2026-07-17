<?php

/**
 * @apiGroup           Customer
 * @apiName            UpdateCustomer
 *
 * @api                {PATCH} /v1/customers/:id Update Customer
 * @apiDescription     Cập nhật thông tin khách hàng (chỉ cập nhật các trường được gửi lên).
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id ID của khách hàng cần cập nhật (HashID)
 *
 * @apiBody            {String} [name] Tên khách hàng mới
 * @apiBody            {String} [phone] Số điện thoại mới
 * @apiBody            {String} [address] Địa chỉ mới
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "object": "Customer",
 *         "id": "XyZ123",
 *         "name": "Nguyễn Văn B",
 *         "phone": "0987654321",
 *         "address": "Hồ Chí Minh"
 *     }
 * }
 */

use App\Containers\AppSection\Order\UI\API\Controllers\UpdateCustomerController;
use Illuminate\Support\Facades\Route;

Route::patch('customers/{id}', [UpdateCustomerController::class, 'updateCustomer'])
    ->middleware(['auth:api']);
