@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<main class="min-h-[calc(100vh-5rem)] bg-[#f4f7f5] px-4 py-8 sm:px-6 lg:flex lg:items-center lg:justify-center lg:py-12">
    <div class="mx-auto grid w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-xl shadow-emerald-950/10 lg:min-h-[600px] lg:grid-cols-2">
        @include('auth.panel', [
            'eyebrow' => 'Petualangan dimulai di sini',
            'heading' => 'Siap menjelajah alam bebas?',
            'text' => 'Temukan perlengkapan camping favoritmu dan siapkan perjalanan yang tak terlupakan.',
        ])

        <section class="flex items-center justify-center px-6 py-8 sm:px-12 lg:px-14">
            <div class="w-full max-w-md">
                <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-2 lg:hidden">
                    <img src="{{ asset('images/Logo.png') }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                    <span class="text-lg font-bold text-gray-900">RentCampy</span>
                </a>

                <div class="mb-8">
                    <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-emerald-700">Selamat datang kembali</p>
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Masuk ke akunmu</h2>
                    <p class="mt-3 text-gray-500">Masukkan detail akun untuk melanjutkan petualangan.</p>
                </div>

                @if (session('info'))
                    <div role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ session('info') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ show: false }">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">Alamat email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" placeholder="nama@email.com"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10">
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">Password</label>
                        <div class="relative">
                            <input id="password" name="password" :type="show ? 'text' : 'password'" type="password" autocomplete="current-password" required placeholder="Masukkan password"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 pr-28 text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-emerald-600 focus:bg-white focus:ring-4 focus:ring-emerald-600/10">
                            <button type="button" @click="show = !show" :aria-pressed="show"
                                    class="absolute inset-y-0 right-0 rounded-r-xl px-4 text-sm font-semibold text-emerald-700 transition hover:text-emerald-900"
                                    x-text="show ? 'Sembunyikan' : 'Lihat'">Lihat</button>
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded accent-emerald-700">
                        Ingat saya
                    </label>

                    @include('auth.error')

                    <button type="submit" class="group flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3.5 font-semibold text-white shadow-lg shadow-emerald-700/20 transition hover:-translate-y-0.5 hover:bg-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-600/20">
                        Masuk
                        <svg aria-hidden="true" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 transition-transform group-hover:translate-x-1">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.22 5.03a.75.75 0 1 1 1.06-1.06l5.45 5.5a.75.75 0 0 1 0 1.06l-5.45 5.5a.75.75 0 0 1-1.06-1.06l4.168-4.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </form>

                <p class="mt-7 text-center text-sm text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-emerald-700 transition hover:text-emerald-900 hover:underline">Daftar sekarang</a>
                </p>
            </div>
        </section>
    </div>
</main>
@endsection
