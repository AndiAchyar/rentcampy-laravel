<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const CATEGORIES = ['Tenda', 'Alat Tidur', 'Peralatan Masak'];

    protected $fillable = ['name', 'category', 'image', 'description', 'price', 'stock'];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
        ];
    }

    /** URL gambar yang siap dipakai di <img src>. */
    public function getImageUrlAttribute(): string
    {
        $image = $this->image;
        if (! $image) {
            return asset('images/welcome-image.png');
        }
        if (str_starts_with($image, 'data:') || str_starts_with($image, 'http')) {
            return $image;
        }

        // Foto bawaan ada di public/images/products.
        if (str_starts_with($image, 'images/')) {
            return asset($image);
        }

        // asset() memakai host dari request saat ini, jadi tidak bergantung pada APP_URL.
        return asset('storage/'.ltrim($image, '/'));
    }

    /** Jumlah unit maksimum yang dipesan pada satu hari mana pun (pesanan aktif). */
    public function maxReservedStock(): int
    {
        $rows = RentalOrderItem::query()
            ->join('rental_orders as o', 'o.id', '=', 'rental_order_items.order_id')
            ->where('rental_order_items.product_id', $this->id)
            ->whereIn('o.status', RentalOrder::ACTIVE_STATUSES)
            ->get(['o.start_date', 'o.end_date', 'rental_order_items.quantity']);

        $changes = [];
        foreach ($rows as $row) {
            $start = strtotime($row->start_date);
            $afterEnd = strtotime($row->end_date) + 86400;
            $changes[$start] = ($changes[$start] ?? 0) + $row->quantity;
            $changes[$afterEnd] = ($changes[$afterEnd] ?? 0) - $row->quantity;
        }
        ksort($changes);

        $reserved = 0;
        $maximum = 0;
        foreach ($changes as $change) {
            $reserved += $change;
            $maximum = max($maximum, $reserved);
        }

        return $maximum;
    }

    public function hasActiveOrders(): bool
    {
        return RentalOrderItem::query()
            ->join('rental_orders as o', 'o.id', '=', 'rental_order_items.order_id')
            ->where('rental_order_items.product_id', $this->id)
            ->whereIn('o.status', RentalOrder::ACTIVE_STATUSES)
            ->exists();
    }
}
