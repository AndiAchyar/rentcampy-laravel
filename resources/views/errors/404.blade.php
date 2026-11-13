@extends('layouts.base')

@section('title', 'Tidak ditemukan')

@section('body')
<main class="flex min-h-screen items-center justify-center bg-[#fbfaf6] px-4 py-12">
    <section class="max-w-md rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm">
        <span class="text-5xl" aria-hidden="true">🏕️</span>
        <h1 class="mt-4 text-2xl font-black text-slate-950">Halaman tidak ditemukan</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">Produk atau halaman yang kamu cari mungkin sudah tidak tersedia.</p>
        <a href="{{ url('/') }}" class="mt-6 inline-flex rounded-full bg-emerald-800 px-6 py-3 text-sm font-bold text-white transition hover:bg-emerald-900">Kembali ke katalog</a>
    </section>
</main>
@endsection
