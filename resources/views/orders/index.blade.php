@extends('layouts.site')

@section('title', 'Pesanan Saya')

@section('content')
<main class="min-h-screen bg-[#f7f8f5] px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Riwayat penyewaan</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Pesanan saya</h1>
            <p class="mt-2 text-slate-500">Lihat status permohonan dan detail jadwal pinjam.</p>
        </div>

        @if ($orders->isEmpty())
            <div class="rounded-3xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
                <span class="text-5xl" aria-hidden="true">📋</span>
                <h2 class="mt-4 text-xl font-bold text-slate-900">Belum ada pesanan</h2>
                <p class="mt-2 text-slate-500">Permohonan penyewaan yang kamu kirim akan muncul di sini.</p>
            </div>
        @else
            <div class="space-y-5">
                @foreach ($orders as $order)
                    @php $status = $order->statusInfo(); @endphp
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div>
                                <p class="font-bold text-slate-900">Pesanan #{{ $order->id }}</p>
                                <p class="mt-1 text-xs text-slate-500">Diajukan {{ waktu($order->created_at) }}</p>
                            </div>
                            <span class="w-fit rounded-full px-3 py-1.5 text-xs font-bold {{ $status['style'] }}">{{ $status['label'] }}</span>
                        </div>
                        <div class="grid gap-5 p-5 sm:grid-cols-[1fr_auto] sm:p-6">
                            <div>
                                <div class="grid gap-3 text-sm sm:grid-cols-2">
                                    <div>
                                        <p class="text-slate-400">Jadwal peminjaman</p>
                                        <p class="mt-1 font-semibold text-slate-800">
                                            {{ tanggal($order->start_date) }} – {{ tanggal($order->end_date) }}
                                            <span class="font-normal text-slate-500">({{ $order->rental_days }} hari)</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-slate-400">Alamat pengiriman</p>
                                        <p class="mt-1 font-medium text-slate-800">{{ $order->delivery_address }}</p>
                                    </div>
                                </div>
                                <ul class="mt-4 divide-y divide-slate-100 rounded-xl bg-slate-50 px-4">
                                    @foreach ($order->items as $item)
                                        <li class="flex justify-between gap-3 py-3 text-sm">
                                            <span class="text-slate-700">{{ $item->product_name }} <span class="text-slate-400">× {{ $item->quantity }}</span></span>
                                            <span class="whitespace-nowrap font-medium text-slate-700">{{ rupiah($item->unit_price * $item->quantity * $order->rental_days) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="flex flex-row items-center justify-between gap-4 border-t border-slate-100 pt-4 sm:min-w-44 sm:flex-col sm:items-end sm:justify-start sm:border-0 sm:pt-0">
                                <div class="sm:text-right">
                                    <p class="text-xs text-slate-400">Total sewa</p>
                                    <p class="mt-1 text-lg font-black text-emerald-800">{{ rupiah($order->total_amount) }}</p>
                                </div>
                                @if ($order->status === 'pending')
                                    <form method="POST" action="{{ route('orders.cancel', $order) }}"
                                          data-confirm="Stok untuk jadwal ini akan dilepas kembali." data-confirm-title="Batalkan permohonan?"
                                          data-confirm-button="Ya, batalkan" data-cancel-button="Kembali" data-confirm-danger>
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50">Batalkan</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</main>
@endsection
