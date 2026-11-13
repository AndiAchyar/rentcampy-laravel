<div class="flex h-full flex-col bg-gray-900 text-white">
    <div class="flex items-center gap-3 border-b border-gray-800 px-5 py-5">
        <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="h-10 w-10 object-contain">
        <div>
            <p class="text-lg font-bold leading-tight">RentCampy</p>
            <p class="text-xs text-gray-400">{{ $isAdmin ? 'Panel Admin' : 'Panel User' }}</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-4">
        <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">Menu</p>
        @foreach ($menu as $item)
            @php $active = request()->routeIs($item['active']); @endphp
            <a href="{{ route($item['route']) }}"
               @class([
                   'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition',
                   'bg-emerald-600 text-white shadow' => $active,
                   'text-gray-300 hover:bg-gray-800 hover:text-white' => ! $active,
               ])>
                <x-icon :name="$item['icon']" />
                <span class="flex-1">{{ $item['label'] }}</span>
                @if (! empty($item['badge']) && $cartCount > 0)
                    <span data-cart-count class="rounded-full bg-amber-400 px-2 py-0.5 text-xs font-bold text-gray-900">{{ $cartCount }}</span>
                @endif
            </a>
        @endforeach
    </nav>

    <div class="border-t border-gray-800 p-4">
        <div class="mb-3 px-1">
            <p class="truncate text-sm font-medium">{{ $user?->email }}</p>
            <span @class([
                'mt-1 inline-block rounded-full px-2 py-0.5 text-xs font-semibold',
                'bg-amber-400 text-gray-900' => $isAdmin,
                'bg-emerald-500/20 text-emerald-300' => ! $isAdmin,
            ])>{{ $isAdmin ? 'Admin' : 'User' }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}"
              data-confirm="Apakah kamu yakin ingin keluar?" data-confirm-title="Log Out"
              data-confirm-button="Ya" data-cancel-button="Tidak">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-300 transition hover:bg-red-500/10">
                <x-icon name="logout" />
                Log Out
            </button>
        </form>
    </div>
</div>
