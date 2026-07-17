<?php

/**
 * @apiGroup           Product
 * @apiName            DeleteProduct
 *
 * @api                {DELETE} /v1/products/:id Delete Product
 * @apiDescription     Xóa mềm một sản phẩm khỏi danh mục.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id ID của sản phẩm cần xóa (HashID)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 204 No Content
 * {
 * }
 */

use App\Containers\AppSection\Order\UI\API\Controllers\DeleteProductController;
use Illuminate\Support\Facades\Route;

Route::delete('products/{id}', [DeleteProductController::class, 'deleteProduct'])
    ->middleware(['auth:api']);
