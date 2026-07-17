<?php

/**
 * @apiGroup           Product
 * @apiName            CreateProduct
 *
 * @api                {POST} /v1/products Create Product
 * @apiDescription     Thêm mới một sản phẩm vào danh mục.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiBody            {String} name Tên sản phẩm (duy nhất)
 * @apiBody            {Number} price Giá bán hiện tại
 * @apiBody            {Number} [stock=0] Số lượng tồn kho ban đầu
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

use App\Containers\AppSection\Order\UI\API\Controllers\CreateProductController;
use Illuminate\Support\Facades\Route;

Route::post('products', [CreateProductController::class, 'createProduct'])
    ->middleware(['auth:api']);
