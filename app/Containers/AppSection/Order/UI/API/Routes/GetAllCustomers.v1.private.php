<?php

/**
 * @apiGroup           Customer
 * @apiName            GetAllCustomers
 *
 * @api                {GET} /v1/customers Get All Customers
 * @apiDescription     Lấy danh sách khách hàng và hỗ trợ tìm kiếm nhanh theo tên hoặc số điện thoại.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {Number} [limit] Số lượng bản ghi trên mỗi trang (mặc định: 15, tối đa: 100)
 * @apiParam           {Number} [page] Trang hiện tại
 * @apiParam           {String} [search] Tìm kiếm theo trường (ví dụ: `name:Nguyen` hoặc `phone:098`)
 * @apiParam           {String} [orderBy] Sắp xếp theo cột (ví dụ: `created_at`)
 * @apiParam           {String} [sortedBy] Hướng sắp xếp (`asc` hoặc `desc`)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": [
 *         {
 *             "object": "Customer",
 *             "id": "XyZ123",
 *             "name": "Nguyễn Văn A",
 *             "phone": "0987654321",
 *             "address": "Hà Nội"
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

use App\Containers\AppSection\Order\UI\API\Controllers\GetAllCustomersController;
use Illuminate\Support\Facades\Route;

Route::get('customers', [GetAllCustomersController::class, 'getAllCustomers'])
    ->middleware(['auth:api']);
