<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekskul Skillo Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen font-sans antialiased text-slate-800">
    <nav class="bg-indigo-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}" class="font-extrabold text-xl tracking-wide">SKILLO ACADEMY</a>
                    <div class="hidden md:flex space-x-2">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-600">Dashboard Admin</a>
                            <a href="{{ route('admin.recap') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-600">Rekap Mingguan & Bulanan</a>
                        @endif
                        <a href="{{ route('guru.calendar') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-600">Kalender Absensi</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right text-sm">
                        <p class="font-semibold">{{ auth()->user()->name }}</p>
                        <span class="text-xs bg-indigo-900 px-2 py-0.5 rounded uppercase">{{ auth()->user()->role }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-md text-sm font-semibold transition cursor-pointer">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="bg-emerald-100 border-l-4 border-emerald-600 text-emerald-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-rose-100 border-l-4 border-rose-600 text-rose-800 p-4 rounded mb-4">
                <ul class="list-disc pl-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>
</body>
</html>
