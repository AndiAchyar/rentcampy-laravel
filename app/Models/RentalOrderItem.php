<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalOrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['order_id', 'product_id', 'product_name', 'category', 'unit_price', 'quantity'];

    protected function casts(): array
    {
        return [
            'unit_price' => 'integer',
            'quantity' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(RentalOrder::class, 'order_id');
    }
}
