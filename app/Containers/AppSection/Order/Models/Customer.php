<?php

namespace App\Containers\AppSection\Order\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends ParentModel
{
    use SoftDeletes;

    protected $fillable = [
        "name",
        "phone",
        "address"
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'Customer';

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
