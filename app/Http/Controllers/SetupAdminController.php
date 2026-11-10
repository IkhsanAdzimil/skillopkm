<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SetupAdminController extends Controller
{
    /**
     * Validasi token dari .env sebelum melanjutkan.
     * Jika token salah → abort 404 (seolah halaman tidak ada).
     */
    private function validateToken(Request $request): void
    {
        $expected = config('app.setup_admin_token');

        if (empty($expected) || $request->query('token') !== $expected) {
            abort(404);
        }
    }

    /**
     * Tampilkan form pembuatan akun admin.
     * Akses: GET /setup-admin?token=TOKEN_RAHASIA
     */
    public function showForm(Request $request)
    {
        $this->validateToken($request);

        $token = $request->query('token');

        return view('setup.admin', compact('token'));
    }

    /**
     * Proses pembuatan akun admin dari form.
     * Akses: POST /setup-admin?token=TOKEN_RAHASIA
     */
    public function store(Request $request)
    {
        $this->validateToken($request);

        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'username'              => 'required|string|max:100|unique:users,username',
            'email'                 => 'required|email|unique:users,email',
            'no_hp'                 => 'nullable|string|max:20',
            'role'                  => 'required|in:admin,guru',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'no_hp'    => $data['no_hp'] ?? null,
            'role'     => $data['role'],
            'password' => $data['password'], // Otomatis di-hash oleh cast 'hashed'
        ]);

        $token = $request->query('token');

        return redirect()
            ->route('setup.admin.form', ['token' => $token])
            ->with('success', 'Akun ' . ucfirst($data['role']) . ' "' . $data['name'] . '" berhasil dibuat! Silakan login.');
    }
}
