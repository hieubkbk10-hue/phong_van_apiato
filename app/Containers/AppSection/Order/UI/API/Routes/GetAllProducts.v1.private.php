<?php

use App\Containers\AppSection\Order\UI\API\Controllers\GetAllProductsController;
use Illuminate\Support\Facades\Route;

Route::get('products', [GetAllProductsController::class, 'getAllProducts'])
    ->middleware(['auth:api']);
