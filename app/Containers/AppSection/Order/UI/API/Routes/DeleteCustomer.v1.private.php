<?php

/**
 * @apiGroup           Customer
 * @apiName            DeleteCustomer
 *
 * @api                {DELETE} /v1/customers/:id Delete Customer
 * @apiDescription     Xóa mềm một khách hàng.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id ID của khách hàng cần xóa (HashID)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 204 No Content
 * {
 * }
 */

use App\Containers\AppSection\Order\UI\API\Controllers\DeleteCustomerController;
use Illuminate\Support\Facades\Route;

Route::delete('customers/{id}', [DeleteCustomerController::class, 'deleteCustomer'])
    ->middleware(['auth:api']);
