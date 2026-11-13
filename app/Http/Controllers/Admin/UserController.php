<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $users = User::query()
            ->when($search !== '', fn ($q) => $q->where('email', 'ILIKE', '%'.addcslashes($search, '%_\\').'%'))
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users', [
            'users' => $users,
            'search' => $search,
            'totalUsers' => User::count(),
        ]);
    }

    public function store(Request $request)
    {
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
        $data = $request->validateWithBag('createUser', [
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', Rule::in(User::ROLES)],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 4 karakter.',
            'role.*' => 'Role tidak valid.',
        ]);

        User::create($data);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(User::ROLES)],
        ], ['role.*' => 'Role tidak valid.']);

        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak bisa mengubah role akun sendiri.');
        }

        $user->update(['role' => $data['role']]);

        return back()->with('success', 'Role berhasil diubah!');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        try {
            $user->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                return back()->with('error', 'User memiliki riwayat peminjaman dan tidak dapat dihapus.');
            }
            throw $e;
        }

        return back()->with('success', 'User berhasil dihapus!');
    }
}
