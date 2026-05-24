<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Funtastic House</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background: #f5f5f7; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <span class="font-bold text-2xl tracking-tight text-gray-900">
                <span style="color:#c99f5b">F</span>untastic<span style="color:#c99f5b">H</span>ouse
            </span>
            <p class="text-sm text-gray-500 mt-1">Painel de Administração</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl p-8" style="box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 10px 40px rgba(0,0,0,0.06)">

            <h1 class="text-xl font-bold text-gray-900 mb-1">Entrar</h1>
            <p class="text-sm text-gray-500 mb-6">Acesso restrito a administradores.</p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-100 rounded-2xl px-4 py-3 mb-5">
                    <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
                @csrf

                {{-- Username --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Utilizador</label>
                    <input
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="admin"
                        autocomplete="username"
                        autofocus
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-300 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                        required
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Senha</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-300 outline-none focus:border-[#c99f5b] focus:ring-2 focus:ring-[#c99f5b]/20 transition"
                        required
                    >
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="rounded" style="accent-color:#c99f5b">
                    <label for="remember" class="text-sm text-gray-600">Manter sessão</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full py-3 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90 active:scale-[.98]"
                    style="background:#c99f5b; box-shadow:0 2px 8px rgba(201,159,91,0.35)"
                >
                    Entrar
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            <a href="{{ url('/') }}" class="hover:text-gray-600 transition">← Voltar ao site público</a>
        </p>

    </div>

</body>
</html>
