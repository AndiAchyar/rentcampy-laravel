@extends('layouts.site')

@section('title', 'Keranjang')

@php
    $cartConfig = [
        'items' => $items->map(fn ($p) => ['id' => $p->id, 'price' => $p->price, 'quantity' => $p->quantity])->values(),
        'selected' => array_map('intval', (array) old('selected', $draft['selected'] ?? $items->pluck('id')->all())),
        'startDate' => old('start_date', $draft['start_date'] ?? ''),
        'endDate' => old('end_date', $draft['end_date'] ?? ''),
        'today' => $today,
    ];
    $fieldClass = 'w-full rounded-xl border border-slate-200 px-3 py-3 text-sm text-slate-700 outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100';
@endphp

@section('content')
<main class="min-h-screen bg-[#f7f8f5] px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-6xl">
        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Checkout penyewaan</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Keranjangmu</h1>
            <p class="mt-2 text-slate-500">Pilih perlengkapan yang ingin disewa. Produk yang tidak dipilih akan tetap tersimpan di keranjang.</p>
        </div>

        @if ($items->isEmpty())
            <section class="rounded-3xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
                <span class="text-5xl" aria-hidden="true">🧺</span>
                <h2 class="mt-4 text-xl font-bold text-slate-900">Keranjang masih kosong</h2>
                <p class="mt-2 text-slate-500">Pilih perlengkapan camping untuk memulai penyewaan.</p>
                <a href="{{ route('home') }}" class="mt-6 inline-flex rounded-full bg-emerald-800 px-6 py-3 text-sm font-bold text-white transition hover:bg-emerald-900">Lihat perlengkapan</a>
            </section>
        @else
            {{-- Form hapus item berada di luar form checkout (form tidak boleh bersarang). --}}
            @foreach ($items as $item)
                <form id="remove-{{ $item->id }}" method="POST" action="{{ route('cart.destroy', $item->id) }}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach

            <form method="POST" action="{{ route('checkout') }}" x-data="cartPage(@js($cartConfig))" class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                @csrf
                <section class="space-y-4">
                    <label class="flex w-fit cursor-pointer items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="checkbox" :checked="allSelected" @change="toggleAll($event.target.checked)" class="h-4 w-4 rounded border-slate-300 accent-emerald-700">
                        <span x-text="'Pilih semua (' + selected.length + '/{{ $items->count() }})'">Pilih semua ({{ $items->count() }}/{{ $items->count() }})</span>
                    </label>

                    @foreach ($items as $index => $item)
                        <article class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="h-36 w-full rounded-xl bg-slate-100 object-cover sm:h-28 sm:w-36">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" name="selected[]" value="{{ $item->id }}" x-model.number="selected"
                                           aria-label="Pilih {{ $item->name }} untuk disewa"
                                           class="mt-1 h-4 w-4 shrink-0 rounded border-slate-300 accent-emerald-700">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">{{ $item->category }}</p>
                                        <h2 class="mt-1 truncate text-lg font-bold text-slate-900">{{ $item->name }}</h2>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm font-semibold text-slate-600">
                                    {{ rupiah($item->price) }} <span class="font-normal text-slate-400">/ unit / hari</span>
                                </p>
                                <div class="mt-3 flex items-center justify-between gap-3">
                                    <label class="flex items-center gap-3 text-sm font-medium text-slate-600">
                                        Jumlah
                                        <input type="number" min="1" max="99" name="quantities[{{ $item->id }}]"
                                               x-model.number="items[{{ $index }}].quantity" @change="saveQuantity(items[{{ $index }}])"
                                               class="w-20 rounded-lg border border-slate-200 px-3 py-2 text-center text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                    </label>
                                    <button type="submit" form="remove-{{ $item->id }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">Hapus</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>

                <section class="h-fit rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <h2 class="text-lg font-bold text-slate-900">Detail penyewaan</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="delivery-address" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat pengiriman</label>
                            <textarea id="delivery-address" name="delivery_address" rows="3" maxlength="500"
                                      placeholder="Nama jalan, nomor rumah, kota..."
                                      class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100">{{ old('delivery_address', $draft['delivery_address'] ?? '') }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="start-date" class="mb-1.5 block text-sm font-semibold text-slate-700">Mulai sewa</label>
                                <input id="start-date" type="date" name="start_date" :min="today" x-model="startDate" @change="onStartChange" class="{{ $fieldClass }}">
                            </div>
                            <div>
                                <label for="end-date" class="mb-1.5 block text-sm font-semibold text-slate-700">Kembali</label>
                                <input id="end-date" type="date" name="end_date" :min="startDate || today" x-model="endDate" class="{{ $fieldClass }}">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3 border-t border-slate-100 pt-5 text-sm">
                        <div class="flex justify-between text-slate-500">
                            <span>Lama sewa</span>
                            <span class="font-semibold text-slate-800" x-text="rentalDays ? rentalDays + ' hari' : 'Pilih tanggal'">Pilih tanggal</span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span><span x-text="selectedItems.length"></span> jenis dipilih</span>
                            <span><span x-text="totalUnits"></span> unit</span>
                        </div>
                        <div class="flex justify-between border-t border-slate-100 pt-4 text-base font-bold text-slate-900">
                            <span>Estimasi total</span>
                            <span x-text="rupiah(total)">Rp 0</span>
                        </div>
                    </div>
                    <p class="mt-3 text-xs leading-5 text-slate-500">
                        Total hanya menghitung produk terpilih. Produk lainnya tetap di keranjang. Tanggal mulai dan kembali sama-sama dihitung sebagai hari sewa; stok dicek server untuk jadwal tersebut.
                    </p>

                    @if ($errors->any())
                        <div role="alert" class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button type="submit" class="mt-5 w-full rounded-full bg-emerald-800 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-950/10 transition hover:bg-emerald-900 disabled:cursor-not-allowed disabled:opacity-60">
                        @auth Kirim permohonan sewa @else Masuk untuk memesan @endauth
                    </button>
                </section>
            </form>
        @endif
    </div>
</main>
@endsection
