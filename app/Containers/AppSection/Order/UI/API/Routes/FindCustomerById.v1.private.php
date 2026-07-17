<?php

/**
 * @apiGroup           Customer
 * @apiName            FindCustomerById
 *
 * @api                {GET} /v1/customers/:id Find Customer By Id
 * @apiDescription     Lấy chi tiết thông tin một khách hàng.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id ID của khách hàng (HashID)
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

use App\Containers\AppSection\Order\UI\API\Controllers\FindCustomerByIdController;
use Illuminate\Support\Facades\Route;

Route::get('customers/{id}', [FindCustomerByIdController::class, 'findCustomerById'])
    ->middleware(['auth:api']);
