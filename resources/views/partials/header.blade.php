@php
    $navClass = fn (bool $active) => 'rounded-full px-4 py-2 text-sm font-semibold transition '.($active
        ? 'bg-emerald-50 text-emerald-800'
        : 'text-slate-600 hover:bg-slate-50 hover:text-emerald-800');
@endphp
<header class="sticky top-0 z-40" x-data="{ menuOpen: false }">
    <div class="border-b border-slate-200/80 bg-white/95 shadow-sm backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="RentCampy, beranda">
                <img src="{{ asset('images/Logo.png') }}" alt="" class="h-11 w-11 object-contain">
                <span class="text-xl font-extrabold tracking-tight text-slate-900">
                    Rent<span class="text-emerald-700">Campy</span>
                </span>
            </a>

            <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
                <a href="{{ route('home') }}" class="{{ $navClass(request()->routeIs('home')) }}">Beranda</a>
                <a href="{{ route('contact') }}" class="{{ $navClass(request()->routeIs('contact')) }}">Hubungi Kami</a>
            </nav>

            <div class="hidden items-center gap-3 md:flex">
                <a href="{{ route('cart') }}"
                   aria-label="Buka keranjang{{ $cartCount ? ", {$cartCount} item" : '' }}"
                   class="relative rounded-full p-2.5 text-slate-600 transition hover:bg-slate-100 hover:text-emerald-800">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                        <path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h8.8a2 2 0 0 0 1.9-1.4L21 8H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="10" cy="20" r="1.4" fill="currentColor"/>
                        <circle cx="18" cy="20" r="1.4" fill="currentColor"/>
                    </svg>
                    @if ($cartCount > 0)
                        <span data-cart-count class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-amber-400 px-1 text-[10px] font-bold text-emerald-950">{{ $cartCount }}</span>
                    @endif
                </a>
                <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Masuk</a>
                <a href="{{ route('register') }}" class="rounded-full bg-emerald-800 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">Daftar</a>
            </div>

            <button type="button" @click="menuOpen = !menuOpen" :aria-expanded="menuOpen"
                    :aria-label="menuOpen ? 'Tutup menu' : 'Buka menu'"
                    class="rounded-xl p-2 text-slate-700 transition hover:bg-slate-100 md:hidden">
                <svg x-show="!menuOpen" viewBox="0 0 24 24" fill="none" class="h-6 w-6" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <svg x-show="menuOpen" x-cloak viewBox="0 0 24 24" fill="none" class="h-6 w-6" aria-hidden="true">
                    <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <nav x-show="menuOpen" x-cloak class="border-t border-slate-100 bg-white px-4 py-4 shadow-lg md:hidden" aria-label="Navigasi mobile">
            <div class="mx-auto flex max-w-7xl flex-col gap-1">
                <a href="{{ route('home') }}" class="{{ $navClass(request()->routeIs('home')) }}">Beranda</a>
                <a href="{{ route('contact') }}" class="{{ $navClass(request()->routeIs('contact')) }}">Hubungi Kami</a>
                <a href="{{ route('cart') }}" class="flex items-center justify-between rounded-full px-4 py-2 text-left text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-emerald-800">
                    Keranjang
                    @if ($cartCount > 0)
                        <span data-cart-count class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-bold text-amber-900">{{ $cartCount }}</span>
                    @endif
                </a>
                <div class="mt-2 grid grid-cols-2 gap-2 border-t border-slate-100 pt-4">
                    <a href="{{ route('login') }}" class="rounded-full border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-emerald-800 px-4 py-2.5 text-center text-sm font-bold text-white transition hover:bg-emerald-900">Daftar</a>
                </div>
            </div>
        </nav>
    </div>
</header>
