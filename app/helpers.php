<?php

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

if (! function_exists('rupiah')) {
    function rupiah(int|float|string|null $value): string
    {
        return 'Rp '.number_format((float) $value, 0, ',', '.');
    }
}

if (! function_exists('tanggal')) {
    /** Contoh: 13 Nov 2026 */
    function tanggal(CarbonInterface|string|null $value): string
    {
        if (! $value) {
            return '-';
        }

        return Carbon::parse($value)->locale('id')->translatedFormat('j M Y');
    }
}

if (! function_exists('waktu')) {
    /** Contoh: 13/11/2026 14.05 */
    function waktu(CarbonInterface|string|null $value): string
    {
        if (! $value) {
            return '-';
        }

        return Carbon::parse($value)->timezone(config('app.timezone'))->format('d/m/Y H.i');
    }
}

if (! function_exists('category_style')) {
    function category_style(?string $category): string
    {
        return [
            'Tenda' => 'bg-emerald-100 text-emerald-800',
            'Alat Tidur' => 'bg-sky-100 text-sky-800',
            'Peralatan Masak' => 'bg-amber-100 text-amber-800',
        ][$category] ?? 'bg-gray-100 text-gray-700';
    }
}
