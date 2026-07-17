<?php

namespace App\Containers\AppSection\Order\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends ParentModel
{
    protected $fillable = [
        'order_code',
        'customer_id',
        'delivery_date',
        'shipping_carrier',
        'payment_method',
        'debt_days',
        'bank_name',
        'bank_account',
        'down_payment',
        'shipping_fee',
        'status',
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'delivery_date' => 'date',
        'down_payment' => 'float',
        'shipping_fee' => 'float',
        'debt_days' => 'integer',
    ];

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'Order';

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
