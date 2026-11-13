@extends('layouts.site')

@section('title', 'Hubungi Kami')

@php
    $inputClass = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100';
@endphp

@section('content')
<main class="min-h-screen bg-[#fbfaf6] px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
    <div class="mx-auto max-w-6xl">
        <section class="relative isolate overflow-hidden rounded-[2rem] bg-emerald-950 px-6 py-10 text-white sm:px-10 sm:py-14 lg:px-14">
            <div class="absolute -right-20 -top-32 -z-10 h-96 w-96 rounded-full bg-emerald-500/30 blur-3xl"></div>
            <div class="absolute -bottom-40 left-1/3 -z-10 h-80 w-80 rounded-full bg-teal-400/20 blur-3xl"></div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-200 sm:text-sm">Kami siap membantu</p>
            <h1 class="mt-3 max-w-2xl text-3xl font-black leading-tight tracking-tight sm:text-5xl">Ada yang ingin ditanyakan sebelum berpetualang?</h1>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-emerald-50/80 sm:text-base">
                Tanyakan tentang perlengkapan, ketersediaan, atau proses penyewaan. Ceritakan kebutuhanmu, kami akan bantu sebisa mungkin.
            </p>
            <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white">
                <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                    <path d="M4 6.5h16v11H4v-11Zm0 1 8 6 8-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kami siap membantu
            </div>
        </section>

        <div class="-mt-5 grid gap-6 px-2 sm:px-5 lg:grid-cols-[0.8fr_1.2fr] lg:gap-8">
            <aside class="space-y-4 pt-10 lg:pt-14">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-800" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                            <path d="M4 6.5h16v11H4v-11Zm0 1 8 6 8-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <h2 class="mt-4 font-bold text-slate-900">Kirim melalui email</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Form akan menyiapkan draft di aplikasi email perangkatmu. Pilih alamat tujuan sebelum mengirim.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-800" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                            <path d="M12 21s7-4.4 7-11a7 7 0 1 0-14 0c0 6.6 7 11 7 11Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                            <circle cx="12" cy="10" r="2.3" stroke="currentColor" stroke-width="1.7"/>
                        </svg>
                    </span>
                    <h2 class="mt-4 font-bold text-slate-900">Tentang penyewaan</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Sertakan nama perlengkapan dan tanggal rencana camping agar kami dapat membantu lebih cepat.</p>
                </div>
                <div class="rounded-2xl bg-emerald-50 p-5 sm:p-6">
                    <p class="text-sm font-bold text-emerald-950">Tips sebelum menghubungi</p>
                    <p class="mt-2 text-sm leading-6 text-emerald-900/75">Cek detail dan stok perlengkapan di katalog. Ketersediaan untuk tanggal tertentu akan diverifikasi saat checkout.</p>
                </div>
            </aside>

            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5 sm:p-8 lg:p-9" x-data="contactForm">
                <div class="mb-6">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">Hubungi RentCampy</p>
                    <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Kirim pesan</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Isi form di bawah. Aplikasi email akan terbuka untuk mengirim pesan ke tim kami.</p>
                </div>

                <div x-show="prepared" x-cloak role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm leading-6 text-emerald-900">
                    Draft pesan sudah disiapkan. Pilih alamat penerima dan kirim melalui aplikasi email untuk menyampaikan pesan.
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="nama" class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                        <input type="text" id="nama" autocomplete="name" required maxlength="100" x-model="nama" @input="prepared = false" placeholder="Nama kamu" class="{{ $inputClass }}">
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" id="email" autocomplete="email" required maxlength="254" x-model="email" @input="prepared = false" placeholder="nama@email.com" class="{{ $inputClass }}">
                    </div>
                    <div>
                        <label for="pesan" class="mb-2 block text-sm font-semibold text-slate-700">Pesan</label>
                        <textarea id="pesan" rows="5" required minlength="10" maxlength="2000" x-model="pesan" @input="prepared = false" placeholder="Ceritakan pertanyaan atau kebutuhanmu..." class="{{ $inputClass }} resize-y"></textarea>
                        <p class="mt-1.5 text-right text-xs text-slate-400"><span x-text="pesan.length">0</span>/2000</p>
                    </div>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-emerald-800 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-950/10 transition hover:-translate-y-0.5 hover:bg-emerald-900 focus:outline-none focus:ring-4 focus:ring-emerald-700/20 sm:w-auto">
                        Buka aplikasi email
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                            <path d="M7 17 17 7M7 7h10v10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </section>
        </div>
    </div>
</main>
@endsection
