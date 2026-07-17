<?php

namespace App\Containers\AppSection\Order\Data\Repositories;

use App\Ship\Parents\Repositories\Repository as ParentRepository;

class OrderItemRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
    ];
}
