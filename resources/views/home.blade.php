@extends('layouts.site')

@php
    $features = [
        ['01', 'Harga bersahabat', 'Sewa harian yang ramah di kantong, tanpa perlu beli perlengkapan sendiri.'],
        ['02', 'Selalu terawat', 'Setiap perlengkapan diperiksa dan dibersihkan sebelum sampai kepadamu.'],
        ['03', 'Semua ada di sini', 'Tenda, alat tidur, hingga perlengkapan masak dalam satu tempat.'],
    ];
@endphp

@section('content')
<div class="overflow-hidden bg-[#fbfaf6] text-slate-900">
    <section class="relative isolate bg-[#f2f1e7]">
        <div class="pointer-events-none absolute -right-32 -top-32 h-[28rem] w-[28rem] rounded-full bg-emerald-200/50 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-48 left-1/3 h-[24rem] w-[24rem] rounded-full bg-amber-100/80 blur-3xl"></div>
        <div class="relative mx-auto grid max-w-7xl items-center gap-8 px-5 py-14 sm:px-8 sm:py-20 lg:grid-cols-[1fr_1.05fr] lg:gap-4 lg:px-10 lg:py-24">
            <div class="relative z-10">
                <span class="mb-6 inline-flex items-center gap-2 rounded-full border border-emerald-900/10 bg-white/70 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-emerald-900 shadow-sm sm:text-sm">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    Petualangan dimulai di sini
                </span>
                <h1 class="max-w-2xl text-4xl font-black leading-[1.08] tracking-tight text-slate-950 sm:text-5xl lg:text-6xl xl:text-7xl">
                    Keluar sebentar,
                    <span class="mt-1 block font-serif font-medium italic text-emerald-800">ceritanya selamanya.</span>
                </h1>
                <p class="mt-6 max-w-xl text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
                    Sewa perlengkapan camping berkualitas tanpa harus membeli semuanya. Pilih gear favoritmu, lalu fokus menikmati alam.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="#produk"
                       class="inline-flex items-center justify-center gap-3 rounded-full bg-emerald-800 px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-950/15 transition hover:-translate-y-0.5 hover:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2">
                        Jelajahi perlengkapan
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                            <path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="rounded-full border border-emerald-900/20 bg-white/60 px-7 py-3.5 text-center text-sm font-bold text-emerald-950 transition hover:bg-white">Buat akun</a>
                    @endguest
                </div>
                <div class="mt-9 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-slate-600">
                    <span class="inline-flex items-center gap-2">
                        <svg viewBox="0 0 20 20" fill="none" class="h-5 w-5 text-emerald-700" aria-hidden="true">
                            <path d="m5 10 3.2 3.2L15.5 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        Gear terawat
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <svg viewBox="0 0 20 20" fill="none" class="h-5 w-5 text-emerald-700" aria-hidden="true">
                            <path d="M10 2.5 12.3 7l5 .7-3.6 3.5.8 5-4.5-2.4-4.5 2.4.8-5-3.6-3.5 5-.7L10 2.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                        </svg>
                        Harga transparan
                    </span>
                </div>
            </div>

            <div class="relative mx-auto flex w-full max-w-2xl items-center justify-center lg:min-h-[30rem]">
                <div class="absolute inset-8 rounded-[3rem] bg-gradient-to-br from-emerald-900 to-emerald-700 shadow-2xl shadow-emerald-950/20 sm:inset-12 sm:rotate-2"></div>
                <div class="absolute right-8 top-8 h-20 w-20 rounded-full border border-white/30 sm:right-14 sm:top-10"></div>
                <img src="{{ asset('images/welcome-image.png') }}" alt="Perlengkapan camping siap menemani petualangan" class="relative z-10 w-full max-w-[34rem] drop-shadow-2xl">
                <div class="absolute bottom-1 left-0 z-20 max-w-[15rem] rounded-2xl border border-white/70 bg-white/90 p-4 shadow-xl backdrop-blur sm:bottom-4 sm:left-2 sm:p-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-xl" aria-hidden="true">🏕️</span>
                        <div>
                            <p class="text-sm font-extrabold text-slate-900">Rencana camping?</p>
                            <p class="mt-0.5 text-xs text-slate-500">Gear-nya biar kami siapkan.</p>
                        </div>
                    </div>
                </div>
                <div class="absolute right-0 top-5 z-20 rounded-full border border-white/70 bg-white/90 px-4 py-2 text-xs font-bold text-emerald-900 shadow-lg sm:right-2 sm:top-10 sm:px-5 sm:py-3 sm:text-sm">
                    Siap untuk alam bebas
                </div>
            </div>
        </div>
    </section>

    <section class="relative z-10 mx-auto -mt-2 max-w-7xl px-5 pb-10 sm:px-8 lg:px-10">
        <div class="grid overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-xl shadow-slate-900/5 md:grid-cols-3">
            @foreach ($features as [$icon, $title, $text])
                <div @class(['flex gap-4 p-6 sm:p-7', 'border-b border-slate-100 md:border-b-0 md:border-r' => ! $loop->last])>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-sm font-black tracking-wide text-emerald-800">{{ $icon }}</span>
                    <div>
                        <h2 class="font-bold text-slate-900">{{ $title }}</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-500">{{ $text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="produk" class="mx-auto max-w-7xl scroll-mt-32 px-5 py-14 sm:px-8 sm:py-20 lg:px-10">
        <div class="mb-8 flex flex-col gap-5 sm:mb-10 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">{{ $isLoggedIn ? 'Katalog lengkap' : 'Favorit penyewa' }}</span>
                <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">{{ $isLoggedIn ? 'Semua perlengkapan' : 'Paling sering disewa' }}</h2>
                <p class="mt-2 text-slate-500">
                    {{ $isLoggedIn ? 'Jelajahi seluruh perlengkapan untuk petualanganmu.' : 'Pilihan perlengkapan yang paling sering disewa pelanggan kami.' }}
                </p>
            </div>
            <div class="text-sm font-medium text-slate-500">
                {{ $totalCount }} {{ $isLoggedIn ? 'perlengkapan tersedia' : 'produk populer' }}
            </div>
        </div>

        <form method="GET" action="{{ route('home') }}#produk" class="mb-8 max-w-lg">
            <label for="product-search" class="sr-only">Cari produk</label>
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input id="product-search" type="search" name="q" value="{{ $keyword }}" placeholder="Cari tenda, kompor, alat tidur..."
                       class="w-full rounded-2xl border border-slate-200 bg-white py-3.5 pl-12 pr-4 text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-4 focus:ring-emerald-100">
            </div>
        </form>

        @if ($products->isEmpty())
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                <span class="text-4xl" aria-hidden="true">🔎</span>
                <p class="mt-4 font-semibold text-slate-700">
                    {{ $keyword !== '' ? "Produk \"{$keyword}\" tidak ditemukan." : 'Belum ada produk.' }}
                </p>
                @if ($keyword !== '')
                    <a href="{{ route('home') }}#produk" class="mt-3 inline-block text-sm font-bold text-emerald-700 hover:text-emerald-900">Hapus pencarian</a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @endif
    </section>

    <footer class="bg-emerald-950 text-white">
        <div class="mx-auto flex max-w-7xl flex-col items-center gap-4 px-5 py-8 text-center sm:px-8 lg:px-10">
            <p class="text-sm text-emerald-100/70">© {{ date('Y') }} RentCampy. Bawa pulang cerita, bukan perlengkapannya.</p>
        </div>
    </footer>
</div>
@endsection
