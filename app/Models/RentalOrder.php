<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RentalOrder extends Model
{
    /** Status yang masih memakan stok. */
    public const ACTIVE_STATUSES = ['pending', 'approved', 'borrowed'];

    public const STATUS = [
        'pending' => ['label' => 'Menunggu persetujuan', 'style' => 'bg-amber-100 text-amber-800'],
        'approved' => ['label' => 'Disetujui', 'style' => 'bg-blue-100 text-blue-800'],
        'borrowed' => ['label' => 'Sedang dipinjam', 'style' => 'bg-violet-100 text-violet-800'],
        'returned' => ['label' => 'Sudah dikembalikan', 'style' => 'bg-emerald-100 text-emerald-800'],
        'rejected' => ['label' => 'Ditolak', 'style' => 'bg-red-100 text-red-800'],
        'cancelled' => ['label' => 'Dibatalkan', 'style' => 'bg-slate-100 text-slate-700'],
    ];

    /** Perubahan status yang boleh dilakukan admin. */
    public const TRANSITIONS = [
        'pending' => ['approved', 'rejected'],
        'approved' => ['borrowed'],
        'borrowed' => ['returned'],
    ];

    /** Tombol aksi admin untuk tiap status. */
    public const ACTIONS = [
        'pending' => [
            ['status' => 'approved', 'label' => 'Setujui', 'style' => 'bg-emerald-700 text-white hover:bg-emerald-800',
                'confirm' => 'Status pesanan akan diperbarui.'],
            ['status' => 'rejected', 'label' => 'Tolak', 'style' => 'border border-red-200 text-red-700 hover:bg-red-50',
                'confirm' => 'Permohonan ditolak dan stok yang dipesan akan dilepas.'],
        ],
        'approved' => [
            ['status' => 'borrowed', 'label' => 'Tandai dipinjam', 'style' => 'bg-blue-700 text-white hover:bg-blue-800',
                'confirm' => 'Status pesanan akan diperbarui.'],
        ],
        'borrowed' => [
            ['status' => 'returned', 'label' => 'Tandai dikembalikan', 'style' => 'bg-emerald-700 text-white hover:bg-emerald-800',
                'confirm' => 'Pastikan barang sudah diterima kembali. Stok akan tersedia lagi untuk jadwal lain.'],
        ],
    ];

    protected $fillable = [
        'user_id', 'status', 'delivery_address', 'start_date', 'end_date', 'rental_days', 'total_amount',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'rental_days' => 'integer',
            'total_amount' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RentalOrderItem::class, 'order_id')->orderBy('id');
    }

    public function statusInfo(): array
    {
        return self::STATUS[$this->status] ?? self::STATUS['pending'];
    }
}
