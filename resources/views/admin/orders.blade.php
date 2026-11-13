@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')

@use('App\Models\RentalOrder')
@php
    $summary = [
        ['Menunggu', $counts['pending'] ?? 0, 'text-amber-700'],
        ['Disetujui', $counts['approved'] ?? 0, 'text-blue-700'],
        ['Dipinjam', $counts['borrowed'] ?? 0, 'text-violet-700'],
        ['Selesai', $counts['returned'] ?? 0, 'text-emerald-700'],
    ];
@endphp

@section('content')
<main class="min-h-screen bg-gray-100 px-4 py-8 md:px-8">
    <div class="mx-auto max-w-6xl">
        <div class="mb-6">
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Operasional</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight text-gray-900">Kelola Peminjaman</h1>
            <p class="mt-2 text-gray-500">Tinjau permohonan, lihat siapa yang meminjam, dan perbarui status pengembalian.</p>
        </div>

        <div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach ($summary as [$label, $count, $color])
                <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">
                    <p class="text-sm text-gray-500">{{ $label }}</p>
                    <p class="mt-1 text-2xl font-black {{ $color }}">{{ $count }}</p>
                </div>
            @endforeach
        </div>

        {{-- Barang yang sedang dipinjam --}}
        <section class="mb-6 rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Barang sedang dipinjam</h2>
                    <p class="mt-1 text-sm text-gray-500">Daftar barang yang sudah diserahkan kepada user.</p>
                </div>
                <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-bold text-violet-800">{{ $borrowedOrders->count() }} pesanan aktif</span>
            </div>

            @if ($borrowedOrders->isEmpty())
                <div class="rounded-lg border border-dashed border-gray-200 py-10 text-center text-sm text-gray-500">Belum ada barang yang sedang dipinjam.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-3 py-3">Pesanan</th>
                                <th class="px-3 py-3">Peminjam</th>
                                <th class="px-3 py-3">Barang dipinjam</th>
                                <th class="px-3 py-3">Jadwal kembali</th>
                                <th class="px-3 py-3">Alamat</th>
                                <th class="px-3 py-3 text-right">Kontrol</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($borrowedOrders as $order)
                                <tr class="border-b last:border-0 hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-3 py-4 font-semibold text-gray-800">#{{ $order->id }}</td>
                                    <td class="px-3 py-4 font-medium text-emerald-800">{{ $order->user->email }}</td>
                                    <td class="px-3 py-4">
                                        <ul class="space-y-1">
                                            @foreach ($order->items as $item)
                                                <li class="font-medium text-gray-800">{{ $item->product_name }} <span class="font-normal text-gray-500">× {{ $item->quantity }}</span></li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-gray-700">
                                        <span class="block">{{ tanggal($order->start_date) }}</span>
                                        <span class="mt-1 block text-xs text-gray-500">Kembali {{ tanggal($order->end_date) }}</span>
                                    </td>
                                    <td class="max-w-xs px-3 py-4 text-gray-600">{{ $order->delivery_address }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-right">
                                        @include('admin.partials.order-action', [
                                            'order' => $order,
                                            'action' => RentalOrder::ACTIONS['borrowed'][0],
                                            'class' => 'px-3 py-2 text-xs',
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Semua pesanan --}}
        <section class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-bold text-gray-900">Daftar pesanan <span class="font-normal text-gray-400">({{ $orders->total() }})</span></h2>
                <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col gap-3 sm:flex-row">
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari email, nomor, produk..."
                           class="rounded-lg border border-gray-200 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    <select name="status" aria-label="Filter status pesanan" onchange="this.form.submit()"
                            class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 outline-none focus:border-emerald-600">
                        <option value="all">Semua status</option>
                        @foreach (RentalOrder::STATUS as $value => $info)
                            <option value="{{ $value }}" @selected($status === $value)>{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if ($orders->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-200 py-14 text-center text-gray-500">Belum ada pesanan pada filter ini.</div>
            @else
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        @php $info = $order->statusInfo(); @endphp
                        <article class="rounded-xl border border-gray-200 p-4 sm:p-5">
                            <div class="flex flex-col gap-3 border-b border-gray-100 pb-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-bold text-gray-900">Pesanan #{{ $order->id }}</h3>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $info['style'] }}">{{ $info['label'] }}</span>
                                    </div>
                                    <p class="mt-2 text-sm font-semibold text-emerald-800">{{ $order->user->email }}</p>
                                    <p class="mt-1 text-xs text-gray-500">Diajukan {{ waktu($order->created_at) }}</p>
                                </div>
                                <div class="sm:text-right">
                                    <p class="text-xs text-gray-400">Total pesanan</p>
                                    <p class="mt-1 text-lg font-black text-gray-900">{{ rupiah($order->total_amount) }}</p>
                                </div>
                            </div>

                            <div class="grid gap-4 py-4 text-sm sm:grid-cols-2">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Jadwal sewa</p>
                                    <p class="mt-1 font-medium text-gray-800">{{ tanggal($order->start_date) }} – {{ tanggal($order->end_date) }} ({{ $order->rental_days }} hari)</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Alamat pengiriman</p>
                                    <p class="mt-1 text-gray-700">{{ $order->delivery_address }}</p>
                                </div>
                            </div>

                            <ul class="divide-y divide-gray-100 rounded-lg bg-gray-50 px-3">
                                @foreach ($order->items as $item)
                                    <li class="flex flex-wrap justify-between gap-2 py-3 text-sm">
                                        <span class="font-medium text-gray-800">{{ $item->product_name }} <span class="font-normal text-gray-500">× {{ $item->quantity }}</span></span>
                                        <span class="text-gray-600">{{ rupiah($item->unit_price) }} / unit / hari</span>
                                    </li>
                                @endforeach
                            </ul>

                            @if (isset(RentalOrder::ACTIONS[$order->status]))
                                <div class="mt-4 flex flex-wrap justify-end gap-2">
                                    @foreach (RentalOrder::ACTIONS[$order->status] as $action)
                                        @include('admin.partials.order-action', ['order' => $order, 'action' => $action, 'class' => 'px-4 py-2 text-sm'])
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif

            {{ $orders->links('vendor.pagination.rentcampy') }}
        </section>
    </div>
</main>
@endsection
