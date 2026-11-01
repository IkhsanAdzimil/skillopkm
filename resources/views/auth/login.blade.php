<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Skillo Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-800 to-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-indigo-600 p-6 text-center text-white">
            <h1 class="text-2xl font-extrabold">SKILLO ACADEMY</h1>
            <p class="text-indigo-100 text-sm mt-1">Sistem Absensi Ekskul Siswa & Guru</p>
        </div>
        <div class="p-8">
            @if($errors->any())
                <div class="bg-rose-100 text-rose-700 p-3 rounded mb-4 text-sm">{{ $errors->first() }}</div>
            @endif
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required autofocus class="w-full border rounded-lg px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full border rounded-lg px-4 py-2.5 text-sm">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg shadow transition cursor-pointer">
                    Login
                </button>
            </form>
            <div class="mt-6 pt-4 border-t text-xs text-gray-600 bg-slate-50 p-3 rounded">
                <p class="font-bold mb-1">Akun Default:</p>
                <p><strong>Admin:</strong> admin@skillo.id | Pass: <code>password123</code></p>
                <p><strong>Guru:</strong> guru@skillo.id | Pass: <code>password123</code></p>
            </div>
        </div>
    </div>
</body>
</html>
