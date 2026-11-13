<div class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-[4/3] overflow-hidden bg-gradient-to-br from-stone-100 via-white to-emerald-50">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" decoding="async"
             class="h-full w-full object-contain p-4 transition duration-300 group-hover:scale-[1.03] sm:p-5">
        <div class="absolute left-3 top-3 flex flex-wrap gap-1.5">
            <span class="rounded-full px-3 py-1 text-xs font-semibold shadow {{ category_style($product->category) }}">{{ $product->category }}</span>
        </div>
        <span @class([
            'absolute bottom-3 right-3 rounded-full px-3 py-1 text-xs font-bold shadow-sm',
            'bg-white/95 text-emerald-800' => $product->stock > 0,
            'bg-slate-900/80 text-white' => $product->stock < 1,
        ])>{{ $product->stock > 0 ? "{$product->stock} tersedia" : 'Stok habis' }}</span>
    </a>

    <div class="flex flex-1 flex-col p-5">
        <h2 class="mb-1 line-clamp-2 min-h-14 text-lg font-semibold text-gray-800">{{ $product->name }}</h2>
        <p class="mb-4 text-lg font-bold text-emerald-700">
            {{ rupiah($product->price) }}<span class="text-sm font-normal text-gray-400"> / hari</span>
        </p>

        <div class="mt-auto grid grid-cols-2 gap-2">
            <a href="{{ route('products.show', $product) }}"
               class="rounded-lg border border-emerald-600 px-3 py-2 text-center text-sm font-medium text-emerald-700 transition hover:bg-emerald-50">Detail</a>
            <form method="POST" action="{{ route('cart.store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" @disabled($product->stock < 1)
                        class="w-full rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-gray-300 disabled:text-gray-500">
                    {{ $product->stock < 1 ? 'Stok habis' : '+ Keranjang' }}
                </button>
            </form>
        </div>
    </div>
</div>
