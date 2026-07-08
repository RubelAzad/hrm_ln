<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HRM') }} - Login</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-CPhVavhb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
</head>
<body class="font-sans antialiased">
    <div class="auth-bg min-h-screen flex items-center justify-center p-4">
        <div class="auth-card">
            <div class="auth-logo">
                <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="brand">{{ config('app.name', 'HR') }}<span>M</span></span>
            </div>

            {{ $slot }}

            <p class="mt-6 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} {{ config('app.name', 'HRM') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
