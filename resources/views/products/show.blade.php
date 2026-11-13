@extends('layouts.site')

@section('title', 'Detail Produk')

@section('content')
<main class="min-h-screen bg-[#fbfaf6] px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <nav aria-label="Breadcrumb" class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="font-medium transition hover:text-emerald-800">Katalog</a>
            <svg viewBox="0 0 20 20" fill="none" class="h-4 w-4 text-slate-300" aria-hidden="true">
                <path d="m7 4 6 6-6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="truncate font-semibold text-slate-800">{{ $product->name }}</span>
        </nav>

        <div class="grid items-start gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:gap-12">
            <section class="relative overflow-hidden rounded-[2rem] border border-emerald-950/5 bg-[#f0efe7] p-5 sm:p-8 lg:sticky lg:top-8">
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-emerald-200/50 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-20 h-64 w-64 rounded-full bg-amber-100/70 blur-3xl"></div>
                <div class="relative flex min-h-[19rem] items-center justify-center sm:min-h-[28rem]">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-h-[28rem] w-full rounded-2xl object-contain drop-shadow-xl">
                </div>
                <div class="relative mt-5 flex flex-wrap gap-2">
                    <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ category_style($product->category) }}">{{ $product->category }}</span>
                </div>
                <div class="relative mt-5 flex items-center gap-3 rounded-2xl border border-white/80 bg-white/80 p-4 backdrop-blur">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                            <path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Siap menemani petualanganmu</p>
                        <p class="mt-0.5 text-xs text-slate-500">Perlengkapan diperiksa sebelum disewakan.</p>
                    </div>
                </div>
            </section>

            <section class="lg:py-5">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">Detail perlengkapan</p>
                <h1 class="mt-3 text-3xl font-black leading-tight tracking-tight text-slate-950 sm:text-4xl lg:text-5xl">{{ $product->name }}</h1>

                <div class="mt-6 flex flex-wrap items-end justify-between gap-4 border-b border-slate-200 pb-6">
                    <div>
                        <p class="text-sm text-slate-500">Harga sewa mulai dari</p>
                        <p class="mt-1 text-3xl font-black tracking-tight text-emerald-800">
                            {{ rupiah($product->price) }}<span class="ml-1 text-sm font-medium text-slate-500">/ unit / hari</span>
                        </p>
                    </div>
                    <span @class([
                        'rounded-full px-3 py-1.5 text-xs font-bold',
                        'bg-emerald-100 text-emerald-800' => $product->stock > 0,
                        'bg-slate-100 text-slate-600' => $product->stock < 1,
                    ])>{{ $product->stock > 0 ? "{$product->stock} unit tersedia" : 'Stok habis' }}</span>
                </div>

                <div class="py-6">
                    <h2 class="text-lg font-bold text-slate-900">Tentang perlengkapan ini</h2>
                    <p class="mt-3 whitespace-pre-line text-base leading-7 text-slate-600">{{ $product->description ?: 'Belum ada deskripsi untuk perlengkapan ini.' }}</p>
                </div>

                <div class="grid gap-3 border-y border-slate-200 py-5 sm:grid-cols-2">
                    <div class="flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-800" aria-hidden="true">✓</span>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Dihitung per hari</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Total akhir mengikuti jumlah barang dan lama sewa.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-800" aria-hidden="true">↗</span>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Cek ketersediaan tanggal</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Stok akan diperiksa saat kamu mengirim permohonan sewa.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" @disabled($product->stock < 1)
                            class="mt-6 flex w-full items-center justify-center gap-3 rounded-full bg-emerald-800 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-emerald-950/10 transition hover:-translate-y-0.5 hover:bg-emerald-900 focus:outline-none focus:ring-4 focus:ring-emerald-700/20 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 disabled:shadow-none disabled:hover:translate-y-0">
                        {{ $product->stock > 0 ? 'Tambahkan ke keranjang' : 'Stok sedang habis' }}
                        @if ($product->stock > 0)
                            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                                <path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @endif
                    </button>
                </form>
                <a href="{{ route('home') }}"
                   class="mt-3 block w-full rounded-full border border-slate-200 bg-white px-6 py-3.5 text-center text-sm font-semibold text-slate-700 transition hover:border-emerald-800 hover:text-emerald-800">
                    Kembali ke katalog
                </a>
            </section>
        </div>
    </div>
</main>
@endsection
