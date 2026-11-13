@extends('layouts.admin')

@section('title', 'Kelola Produk')

@php
    $inputClass = 'w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200';
    $isEditing = (bool) $editing;
    $value = fn (string $field, $default = '') => old($field, $editing?->{$field} ?? $default);
@endphp

@section('content')
<div class="min-h-screen bg-gray-100 px-4 py-8">
    <div class="mx-auto max-w-6xl">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Kelola Produk</h1>
            <p class="text-gray-500">Tambah, ubah, dan hapus produk RentCampy</p>
        </div>

        {{-- Form tambah / edit --}}
        <form method="POST" enctype="multipart/form-data"
              action="{{ $isEditing ? route('admin.products.update', $editing) : route('admin.products.store') }}"
              class="mb-8 rounded-xl bg-white p-6 shadow-sm">
            @csrf
            @if ($isEditing)
                @method('PUT')
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="page" value="{{ request('page') }}">
            @endif

            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">{{ $isEditing ? "Edit Produk: {$editing->name}" : 'Tambah Produk Baru' }}</h2>
                @if ($isEditing)
                    <a href="{{ route('admin.products.index', request()->only('search', 'page')) }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">Batal edit</a>
                @endif
            </div>

            @if ($errors->any())
                <div role="alert" class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input id="name" type="text" name="name" required maxlength="255" value="{{ $value('name') }}" class="{{ $inputClass }}">
                </div>
                <div>
                    <label for="category" class="mb-1 block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="category" name="category" required class="{{ $inputClass }}">
                        <option value="">Pilih...</option>
                        @foreach (\App\Models\Product::CATEGORIES as $cat)
                            <option value="{{ $cat }}" @selected($value('category') === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="price" class="mb-1 block text-sm font-medium text-gray-700">Harga per hari (Rp)</label>
                    <input id="price" type="number" name="price" min="0" required placeholder="50000" value="{{ $value('price') }}" class="{{ $inputClass }}">
                </div>
                <div>
                    <label for="stock" class="mb-1 block text-sm font-medium text-gray-700">Stok total (unit)</label>
                    <input id="stock" type="number" name="stock" min="0" step="1" required value="{{ $value('stock', 0) }}" class="{{ $inputClass }}">
                    <p class="mt-1 text-xs text-gray-500">Jumlah perlengkapan fisik yang tersedia untuk disewakan.</p>
                </div>
                <div>
                    <label for="image" class="mb-1 block text-sm font-medium text-gray-700">
                        Gambar Produk
                        @if ($isEditing)
                            <span class="font-normal text-gray-400">(kosongkan jika tidak ingin mengganti gambar)</span>
                        @endif
                    </label>
                    <input id="image" type="file" name="image" accept="image/*" @required(! $isEditing)
                           class="block w-full rounded-lg border border-gray-300 bg-white text-sm file:mr-3 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-sm">
                    <p class="mt-1 text-xs text-gray-500">Format gambar, maksimal 5 MB.</p>
                </div>
                @if ($isEditing)
                    <div class="flex items-center gap-3">
                        <img src="{{ $editing->image_url }}" alt="Gambar saat ini" class="h-20 w-20 rounded-lg object-cover">
                        <span class="text-xs text-gray-500">Gambar saat ini</span>
                    </div>
                @endif
                <div class="md:col-span-2">
                    <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="3" required class="{{ $inputClass }}">{{ $value('description') }}</textarea>
                </div>
            </div>

            <button type="submit" class="mt-5 rounded-lg bg-emerald-700 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-300">
                {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Produk' }}
            </button>
        </form>

        {{-- Tabel produk --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Daftar Produk</h2>
                <form method="GET" action="{{ route('admin.products.index') }}" class="sm:max-w-xs sm:flex-1">
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama produk..." class="{{ $inputClass }}">
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-3 py-3">No</th>
                            <th class="px-3 py-3">Gambar</th>
                            <th class="px-3 py-3">Nama</th>
                            <th class="px-3 py-3">Kategori</th>
                            <th class="px-3 py-3">Deskripsi</th>
                            <th class="px-3 py-3">Harga</th>
                            <th class="px-3 py-3">Stok</th>
                            <th class="px-3 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr @class(['border-b last:border-0 hover:bg-gray-50', 'bg-emerald-50' => $editing?->id === $product->id])>
                                <td class="px-3 py-3">{{ $products->firstItem() + $loop->index }}</td>
                                <td class="px-3 py-3">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-14 w-14 rounded-lg object-cover">
                                </td>
                                <td class="px-3 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                                <td class="px-3 py-3">
                                    <span class="whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium {{ category_style($product->category) }}">{{ $product->category }}</span>
                                </td>
                                <td class="max-w-xs px-3 py-3 text-gray-600"><p class="line-clamp-2">{{ $product->description }}</p></td>
                                <td class="whitespace-nowrap px-3 py-3 font-medium">{{ rupiah($product->price) }}</td>
                                <td class="whitespace-nowrap px-3 py-3 font-semibold text-gray-700">{{ $product->stock }} unit</td>
                                <td class="whitespace-nowrap px-3 py-3 text-center">
                                    <a href="{{ route('admin.products.index', array_filter(['edit' => $product->id, 'search' => $search, 'page' => request('page')])) }}"
                                       class="mr-2 inline-block rounded-lg bg-green-100 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-200">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline"
                                          data-confirm="Produk &quot;{{ $product->name }}&quot; akan dihapus permanen." data-confirm-title="Hapus produk ini?"
                                          data-confirm-button="Hapus" data-confirm-danger>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-200">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-3 py-10 text-center text-gray-400">Tidak ada produk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $products->links('vendor.pagination.rentcampy') }}
        </div>
    </div>
</div>
@endsection
