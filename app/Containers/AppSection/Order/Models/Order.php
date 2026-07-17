<?php

namespace App\Containers\AppSection\Order\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends ParentModel
{
    // Trạng thái đơn hàng
    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    // Phương thức thanh toán
    public const PAYMENT_COD = 'COD';

    public const PAYMENT_CASH = 'CASH';

    public const PAYMENT_BANK_TRANSFER = 'BANK_TRANSFER';

    public const PAYMENT_DEBT = 'DEBT';

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
