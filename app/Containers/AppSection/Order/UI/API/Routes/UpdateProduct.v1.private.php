<?php

/**
 * @apiGroup           Product
 * @apiName            UpdateProduct
 *
 * @api                {PATCH} /v1/products/:id Update Product
 * @apiDescription     Cập nhật thông tin sản phẩm (chỉ cập nhật các trường gửi lên).
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id ID của sản phẩm cần cập nhật (HashID)
 *
 * @apiBody            {String} [name] Tên sản phẩm mới
 * @apiBody            {Number} [price] Giá bán mới
 * @apiBody            {Number} [stock] Số lượng tồn kho mới
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "object": "Product",
 *         "id": "AbC456",
 *         "name": "Điện thoại iPhone 15 Pro Max",
 *         "price": 30000000,
 *         "stock": 48
 *     }
 * }
 */

use App\Containers\AppSection\Order\UI\API\Controllers\UpdateProductController;
use Illuminate\Support\Facades\Route;

Route::patch('products/{id}', [UpdateProductController::class, 'updateProduct'])
    ->middleware(['auth:api']);
