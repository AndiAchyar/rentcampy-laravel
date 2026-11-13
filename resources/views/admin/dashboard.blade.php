@extends('layouts.admin')

@section('title', 'Ringkasan Admin')

@php
    $cards = [
        ['Total Produk', $totalProducts],
        ['Total User', $totalUsers],
        ['Total Peminjaman', $totalOrders],
        ['Perlu Ditinjau', $pendingOrders],
    ];
    foreach ($byCategory as $c) {
        $cards[] = ["Kategori {$c->category}", $c->count];
    }
@endphp

@section('content')
<div class="px-4 py-8 md:px-8">
    <div class="mx-auto max-w-6xl">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Ringkasan</h1>
            <p class="text-gray-500">Selamat datang, {{ auth()->user()->email }}</p>
        </div>

        <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-6">
            @foreach ($cards as [$label, $value])
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">{{ $label }}</p>
                    <p class="mt-1 text-3xl font-bold text-gray-800">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-amber-100 bg-amber-50 p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Permohonan peminjaman</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ $pendingOrders }} pesanan menunggu keputusan admin.</p>
                    </div>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="shrink-0 rounded-lg bg-emerald-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-900">Kelola →</a>
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-800">Produk per Kategori</h2>
                @forelse ($byCategory as $c)
                    <div class="mb-4 last:mb-0">
                        <div class="mb-1 flex justify-between text-sm">
                            <span class="text-gray-700">{{ $c->category }}</span>
                            <span class="font-medium text-gray-800">{{ $c->count }}</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-gray-100">
                            <div class="h-2.5 rounded-full bg-emerald-500" style="width: {{ round($c->count / $maxCount * 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400">Belum ada produk.</p>
                @endforelse
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Produk Terbaru</h2>
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-emerald-600 hover:underline">Kelola produk →</a>
                </div>
                @if ($latestProducts->isEmpty())
                    <p class="text-gray-400">Belum ada produk.</p>
                @else
                    <ul class="divide-y">
                        @foreach ($latestProducts as $p)
                            <li class="flex items-center gap-3 py-3">
                                <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="h-12 w-12 rounded-lg object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-medium text-gray-800">{{ $p->name }}</p>
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ category_style($p->category) }}">{{ $p->category }}</span>
                                </div>
                                <p class="whitespace-nowrap text-sm font-medium text-gray-700">{{ rupiah($p->price) }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
