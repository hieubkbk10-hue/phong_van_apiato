<?php

/**
 * @apiGroup           Customer
 * @apiName            CreateCustomer
 *
 * @api                {POST} /v1/customers Create Customer
 * @apiDescription     Tạo mới một khách hàng.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiBody            {String} name Tên khách hàng
 * @apiBody            {String} phone Số điện thoại (phải là duy nhất)
 * @apiBody            {String} address Địa chỉ khách hàng
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "object": "Customer",
 *         "id": "XyZ123",
 *         "name": "Nguyễn Văn A",
 *         "phone": "0987654321",
 *         "address": "Hà Nội"
 *     }
 * }
 */

use App\Containers\AppSection\Order\UI\API\Controllers\CreateCustomerController;
use Illuminate\Support\Facades\Route;

Route::post('customers', [CreateCustomerController::class, 'createCustomer'])
    ->middleware(['auth:api']);
