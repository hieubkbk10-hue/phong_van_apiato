<?php

/**
 * @apiGroup           Product
 * @apiName            GetAllProducts
 *
 * @api                {GET} /v1/products Get All Products
 * @apiDescription     Lấy danh sách sản phẩm mẫu và hỗ trợ tìm kiếm theo tên sản phẩm.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {Number} [limit] Số lượng bản ghi trên mỗi trang (mặc định: 15, tối đa: 100)
 * @apiParam           {Number} [page] Trang hiện tại
 * @apiParam           {String} [search] Tìm kiếm theo trường (ví dụ: `name:Iphone`)
 * @apiParam           {String} [orderBy] Sắp xếp theo cột (ví dụ: `price`)
 * @apiParam           {String} [sortedBy] Hướng sắp xếp (`asc` hoặc `desc`)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": [
 *         {
 *             "object": "Product",
 *             "id": "AbC456",
 *             "name": "Điện thoại iPhone 15 Pro Max",
 *             "price": 30000000,
 *             "stock": 100
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

use App\Containers\AppSection\Order\UI\API\Controllers\GetAllProductsController;
use Illuminate\Support\Facades\Route;

Route::get('products', [GetAllProductsController::class, 'getAllProducts'])
    ->middleware(['auth:api']);
