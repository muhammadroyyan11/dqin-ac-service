<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — DQIN AC Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-lg font-black text-white shadow-lg shadow-slate-950/20">
                        D
                    </div>
                    <div class="text-left">
                        <div class="text-xl font-bold tracking-tight text-slate-950">DQIN AC</div>
                        <div class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Admin Portal</div>
                    </div>
                </a>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 sm:p-10">
                <div class="mb-8 text-center">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-950">Welcome Back</h1>
                    <p class="mt-2 text-sm text-slate-500">Sign in to manage DQIN AC operations.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@dqin-ac.com" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">Forgot?</a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <label class="flex items-center gap-3 text-sm text-slate-600">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        Remember me
                    </label>

                    <button type="submit" class="flex w-full items-center justify-center rounded-2xl bg-slate-950 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-slate-950/20 transition hover:bg-blue-600">
                        Sign In
                    </button>
                </form>

                <div class="mt-6 rounded-2xl bg-slate-50 p-4 text-center text-sm text-slate-500">
                    Demo: <span class="font-semibold text-slate-800">admin@dqin-ac.com</span> / <span class="font-semibold text-slate-800">password</span>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
