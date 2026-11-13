@props(['role' => 'user'])
@php
    $isAdmin = $role === 'admin';
    $menu = $isAdmin
        ? [
            ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'label' => 'Ringkasan', 'icon' => 'chart-pie'],
            ['route' => 'admin.products.index', 'active' => 'admin.products.*', 'label' => 'Kelola Produk', 'icon' => 'cube'],
            ['route' => 'admin.orders.index', 'active' => 'admin.orders.*', 'label' => 'Peminjaman', 'icon' => 'clipboard-list'],
            ['route' => 'admin.users.index', 'active' => 'admin.users.*', 'label' => 'Kelola User', 'icon' => 'users'],
        ]
        : [
            ['route' => 'home', 'active' => 'home', 'label' => 'Katalog', 'icon' => 'home'],
            ['route' => 'cart', 'active' => 'cart', 'label' => 'Keranjang', 'icon' => 'shopping-cart', 'badge' => true],
            ['route' => 'orders.index', 'active' => 'orders.*', 'label' => 'Pesanan Saya', 'icon' => 'clipboard-list'],
        ];
    $user = auth()->user();
    $cartCount = $cartCount ?? \App\Support\Cart::count();
@endphp

<div class="min-h-screen bg-gray-100 md:flex" x-data="{ open: false }">
    {{-- Sidebar desktop --}}
    <aside class="hidden md:sticky md:top-0 md:block md:h-screen md:w-64 md:shrink-0">
        @include('partials.sidebar', compact('isAdmin', 'menu', 'user', 'cartCount'))
    </aside>

    {{-- Top bar mobile --}}
    <div class="flex items-center justify-between bg-gray-900 px-4 py-3 text-white md:hidden">
        <p class="text-lg font-bold">RentCampy</p>
        <button type="button" @click="open = true" aria-label="Buka menu">
            <x-icon name="menu" class="h-6 w-6" />
        </button>
    </div>

    {{-- Drawer mobile --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-50 md:hidden">
        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
        <aside class="absolute left-0 top-0 h-full w-64 shadow-xl">
            <button type="button" @click="open = false" aria-label="Tutup menu" class="absolute right-3 top-4 z-10 text-gray-300">
                <x-icon name="x" class="h-6 w-6" />
            </button>
            @include('partials.sidebar', compact('isAdmin', 'menu', 'user', 'cartCount'))
        </aside>
    </div>

    <main class="min-w-0 flex-1">
        {{ $slot }}
    </main>
</div>
