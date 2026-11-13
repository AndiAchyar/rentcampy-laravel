@extends('layouts.admin')

@section('title', 'Kelola User')

@php
    $inputClass = 'w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200';
    $roleStyle = ['admin' => 'bg-amber-100 text-amber-700', 'user' => 'bg-emerald-100 text-emerald-700'];
    $createErrors = $errors->getBag('createUser');
@endphp

@section('content')
<div class="px-4 py-8 md:px-8" x-data="{ adding: {{ $createErrors->any() ? 'true' : 'false' }} }">
    <div class="mx-auto max-w-6xl">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Kelola User</h1>
                <p class="text-gray-500">Lihat, tambah, ubah role, dan hapus akun</p>
            </div>
            <button type="button" @click="adding = true" class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">+ Tambah User</button>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Daftar Akun <span class="text-base font-normal text-gray-400">({{ $totalUsers }})</span></h2>
                <form method="GET" action="{{ route('admin.users.index') }}" class="sm:max-w-xs sm:flex-1">
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari email..." class="{{ $inputClass }}">
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-3 py-3">No</th>
                            <th class="px-3 py-3">Email</th>
                            <th class="px-3 py-3">Role</th>
                            <th class="px-3 py-3">Terdaftar</th>
                            <th class="px-3 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php $isMe = $user->is(auth()->user()); @endphp
                            <tr class="border-b last:border-0 hover:bg-gray-50">
                                <td class="px-3 py-3">{{ $users->firstItem() + $loop->index }}</td>
                                <td class="px-3 py-3 font-medium text-gray-800">
                                    {{ $user->email }}
                                    @if ($isMe) <span class="ml-2 text-xs font-normal text-gray-400">(Anda)</span> @endif
                                </td>
                                <td class="px-3 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $roleStyle[$user->role] ?? '' }}">{{ $user->role === 'admin' ? 'Admin' : 'User' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-gray-600">{{ tanggal($user->created_at) }}</td>
                                <td class="whitespace-nowrap px-3 py-3 text-center">
                                    @if ($isMe)
                                        <span class="text-xs text-gray-400">—</span>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.role', $user) }}" class="mr-2 inline-flex items-center gap-1"
                                              data-confirm="Role {{ $user->email }} akan diubah." data-confirm-title="Ubah role?" data-confirm-button="Simpan">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" aria-label="Role untuk {{ $user->email }}" class="rounded-lg border border-gray-200 py-1 pl-2 pr-7 text-xs">
                                                <option value="user" @selected($user->role === 'user')>User</option>
                                                <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                            </select>
                                            <button type="submit" class="rounded-lg bg-green-100 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-200">Ubah Role</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                              data-confirm="Akun {{ $user->email }} akan dihapus permanen." data-confirm-title="Hapus user ini?"
                                              data-confirm-button="Hapus" data-confirm-danger>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-200">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-3 py-10 text-center text-gray-400">Tidak ada user.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $users->links('vendor.pagination.rentcampy') }}
        </div>
    </div>

    {{-- Modal tambah user --}}
    <div x-show="adding" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="adding = false">
        <div class="absolute inset-0 bg-black/50" @click="adding = false"></div>
        <form method="POST" action="{{ route('admin.users.store') }}" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            @csrf
            <h2 class="mb-4 text-xl font-bold text-gray-800">Tambah User</h2>

            @if ($createErrors->any())
                <div role="alert" class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $createErrors->first() }}</div>
            @endif

            <div class="space-y-3">
                <div>
                    <label for="new-email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                    <input id="new-email" type="email" name="email" required value="{{ old('email') }}" class="{{ $inputClass }}">
                </div>
                <div>
                    <label for="new-password" class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                    <input id="new-password" type="password" name="password" required minlength="4" placeholder="Minimal 4 karakter" class="{{ $inputClass }}">
                </div>
                <div>
                    <label for="new-role" class="mb-1 block text-sm font-medium text-gray-700">Role</label>
                    <select id="new-role" name="role" class="{{ $inputClass }}">
                        <option value="user" @selected(old('role') !== 'admin')>User</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" @click="adding = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-medium text-white hover:bg-emerald-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
