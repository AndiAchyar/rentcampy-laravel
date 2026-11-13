@extends('layouts.guest')

@section('title', 'Daftar')

@php
    $inputClass = 'w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 pr-28 text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10';
@endphp

@section('content')
<main class="min-h-[calc(100vh-5rem)] bg-[#f4f7f5] px-4 py-8 sm:px-6 lg:flex lg:items-center lg:justify-center lg:py-12">
    <div class="mx-auto grid w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-xl shadow-emerald-950/10 lg:min-h-[640px] lg:grid-cols-2">
        @include('auth.panel', [
            'eyebrow' => 'Mulai petualanganmu',
            'heading' => 'Buat cerita baru di alam terbuka.',
            'text' => 'Daftar untuk menemukan dan menyewa perlengkapan camping sesuai kebutuhanmu.',
        ])

        <section class="flex items-center justify-center px-6 py-8 sm:px-12 lg:px-14">
            <div class="w-full max-w-md">
                <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-2 lg:hidden">
                    <img src="{{ asset('images/Logo.png') }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                    <span class="text-lg font-bold text-gray-900">RentCampy</span>
                </a>

                <div class="mb-8">
                    <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-emerald-700">Akun baru</p>
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Daftar sekarang</h2>
                    <p class="mt-3 text-gray-500">Buat akun untuk mulai menyewa perlengkapan camping.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ show: false, showConfirm: false }">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">Alamat email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" placeholder="nama@email.com"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10">
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">Password</label>
                        <div class="relative">
                            <input id="password" name="password" :type="show ? 'text' : 'password'" type="password" autocomplete="new-password" required minlength="4" placeholder="Minimal 4 karakter" class="{{ $inputClass }}">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 rounded-r-xl px-4 text-sm font-semibold text-emerald-700 hover:text-emerald-900" x-text="show ? 'Sembunyikan' : 'Lihat'">Lihat</button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-gray-700">Konfirmasi password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'" type="password" autocomplete="new-password" required placeholder="Ulangi password" class="{{ $inputClass }}">
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 rounded-r-xl px-4 text-sm font-semibold text-emerald-700 hover:text-emerald-900" x-text="showConfirm ? 'Sembunyikan' : 'Lihat'">Lihat</button>
                        </div>
                    </div>

                    @include('auth.error')

                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3.5 font-semibold text-white shadow-lg shadow-emerald-700/20 transition hover:-translate-y-0.5 hover:bg-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-600/20">
                        Buat akun
                    </button>
                </form>

                <p class="mt-7 text-center text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-emerald-700 transition hover:text-emerald-900 hover:underline">Masuk</a>
                </p>
            </div>
        </section>
    </div>
</main>
@endsection
