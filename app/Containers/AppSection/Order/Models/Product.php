<?php

namespace App\Containers\AppSection\Order\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends ParentModel
{
    use SoftDeletes;

    protected $fillable = [
        "name",
        "price",
        "stock"
    ];

    protected $hidden = [

    ];

    protected $casts = [
        "price" => "float",
        "stock" => "integer",
    ];

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'Product';

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
