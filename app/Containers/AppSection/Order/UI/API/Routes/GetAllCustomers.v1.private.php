<?php

use App\Containers\AppSection\Order\UI\API\Controllers\GetAllCustomersController;
use Illuminate\Support\Facades\Route;

Route::get('customers', [GetAllCustomersController::class, 'getAllCustomers'])
    ->middleware(['auth:api']);
