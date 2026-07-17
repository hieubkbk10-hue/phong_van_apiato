<?php

/**
 * @apiGroup           Product
 * @apiName            FindProductById
 *
 * @api                {GET} /v1/products/:id Find Product By Id
 * @apiDescription     Lấy chi tiết thông tin một sản phẩm.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id ID của sản phẩm (HashID)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "object": "Product",
 *         "id": "AbC456",
 *         "name": "Điện thoại iPhone 15 Pro",
 *         "price": 28000000,
 *         "stock": 50
 *     }
 * }
 */

use App\Containers\AppSection\Order\UI\API\Controllers\FindProductByIdController;
use Illuminate\Support\Facades\Route;

Route::get('products/{id}', [FindProductByIdController::class, 'findProductById'])
    ->middleware(['auth:api']);
