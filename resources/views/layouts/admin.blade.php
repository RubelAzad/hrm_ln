<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'HRM') }}</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-CPhVavhb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
</head>
<body class="antialiased">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4.5 h-4.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <a href="{{ route('employees.index') }}" class="text-base font-bold text-white tracking-tight block leading-tight">
                            {{ config('app.name', 'HR') }}<span class="text-indigo-400">M</span>
                        </a>
                        <p class="text-[10px] text-slate-500 font-medium tracking-wider uppercase">Human Resource Manager</p>
                    </div>
                </div>
            </div>

            <nav class="sidebar-nav" x-data="sidebarAccordion()">
                {{-- Employees --}}
                <button @click="toggle('employees')" class="sidebar-section group w-full flex items-center justify-between cursor-pointer text-left">
                    <span>Employees</span>
                    <svg class="w-3 h-3 text-slate-600 transition-transform duration-200" :class="{ 'rotate-180': open === 'employees' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === 'employees'" x-collapse.duration.200ms>
                    <a href="{{ route('employees.index') }}"
                       class="sidebar-link {{ request()->routeIs('employees.*') && !request()->routeIs('employees.org-chart') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        All Employees
                    </a>
                    <a href="{{ route('employees.org-chart') }}"
                       class="sidebar-link {{ request()->routeIs('employees.org-chart') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Organization Chart
                    </a>
                </div>

                {{-- Recruitment --}}
                <button @click="toggle('recruitment')" class="sidebar-section group w-full flex items-center justify-between cursor-pointer text-left">
                    <span>Recruitment</span>
                    <svg class="w-3 h-3 text-slate-600 transition-transform duration-200" :class="{ 'rotate-180': open === 'recruitment' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === 'recruitment'" x-collapse.duration.200ms>
                    <a href="{{ route('recruitment.dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('recruitment.dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('recruitment.candidates.index') }}"
                       class="sidebar-link {{ request()->routeIs('recruitment.candidates.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Candidates
                    </a>
                    <a href="{{ route('recruitment.jobs.index') }}"
                       class="sidebar-link {{ request()->routeIs('recruitment.jobs.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Job Postings
                    </a>
                    <a href="{{ route('recruitment.interviews.index') }}"
                       class="sidebar-link {{ request()->routeIs('recruitment.interviews.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Interviews
                    </a>
                    <a href="{{ route('recruitment.talent-pools.index') }}"
                       class="sidebar-link {{ request()->routeIs('recruitment.talent-pools.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Talent Pools
                    </a>
                </div>

                {{-- AI Features --}}
                <button @click="toggle('ai')" class="sidebar-section group w-full flex items-center justify-between cursor-pointer text-left">
                    <span>AI Features</span>
                    <svg class="w-3 h-3 text-slate-600 transition-transform duration-200" :class="{ 'rotate-180': open === 'ai' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === 'ai'" x-collapse.duration.200ms>
                    <a href="{{ route('recruitment.jobs.index') }}"
                       class="sidebar-link {{ request()->routeIs('ai.*') || (request()->routeIs('recruitment.jobs.*') && request()->routeIs('*.show')) ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Candidate Ranking
                    </a>
                </div>

                {{-- Attendance --}}
                <button @click="toggle('attendance')" class="sidebar-section group w-full flex items-center justify-between cursor-pointer text-left">
                    <span>Attendance</span>
                    <svg class="w-3 h-3 text-slate-600 transition-transform duration-200" :class="{ 'rotate-180': open === 'attendance' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === 'attendance'" x-collapse.duration.200ms>
                    <a href="{{ route('attendance.index') }}"
                       class="sidebar-link {{ request()->routeIs('attendance.index') || request()->routeIs('attendance.show') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Records
                    </a>
                    <a href="{{ route('attendance.check-in') }}"
                       class="sidebar-link {{ request()->routeIs('attendance.check-in*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Check In
                    </a>
                    <a href="{{ route('shifts.index') }}"
                       class="sidebar-link {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Shifts
                    </a>
                    <a href="{{ route('attendance.overtime') }}"
                       class="sidebar-link {{ request()->routeIs('attendance.overtime*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Overtime
                    </a>
                    <a href="{{ route('attendance.anomalies') }}"
                       class="sidebar-link {{ request()->routeIs('attendance.anomalies*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Anomalies
                    </a>
                    <a href="{{ route('attendance.missing-punches') }}"
                       class="sidebar-link {{ request()->routeIs('attendance.missing-punches') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Missing Punches
                    </a>
                    <a href="{{ route('geo-fences.index') }}"
                       class="sidebar-link {{ request()->routeIs('geo-fences.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Geo-Fences
                    </a>
                </div>

                {{-- Modules --}}
                <button @click="toggle('modules')" class="sidebar-section group w-full flex items-center justify-between cursor-pointer text-left">
                    <span>Modules</span>
                    <svg class="w-3 h-3 text-slate-600 transition-transform duration-200" :class="{ 'rotate-180': open === 'modules' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === 'modules'" x-collapse.duration.200ms>
                    <a href="#" class="sidebar-link disabled">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Payroll
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user" x-data="{ open: false }" @click="open = !open" @click.away="open = false">
                    <div class="sidebar-user-avatar">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-200 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-500" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute bottom-16 left-3 right-3 bg-slate-800 rounded-lg border border-slate-700 shadow-xl py-1 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700/50">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700/50">Sign out</button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="main-content">
            <header class="main-header">
                <h1>@yield('title', 'Dashboard')</h1>
                <div class="flex items-center gap-3">
                    <a href="{{ route('recruitment.candidates.create') }}" class="btn-primary text-xs px-3 py-1.5">
                        + Add Candidate
                    </a>
                    <a href="{{ route('recruitment.jobs.create') }}" class="btn-secondary text-xs px-3 py-1.5">
                        + Post Job
                    </a>
                </div>
            </header>

            @if (session('success'))
                <div class="bg-emerald-50 border-b border-emerald-200">
                    <div class="px-6 py-3">
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="main-body">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('build/assets/app-tUMsq8Mz.js') }}" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sidebarAccordion', () => ({
                open: (() => {
                    const route = '{{ request()->route()->getName() ?? "" }}';
                    if (route.startsWith('employees.')) return 'employees';
                    if (route.startsWith('recruitment.')) return 'recruitment';
                    if (route.startsWith('ai.')) return 'ai';
                    if (route.startsWith('attendance.') || route.startsWith('shifts.') || route.startsWith('geo-fences.')) return 'attendance';
                    if (route.startsWith('profile.')) return '';
                    return 'employees';
                })(),
                toggle(section) {
                    this.open = this.open === section ? '' : section;
                }
            }));
        });
    </script>
    @stack('scripts')
</body>
</html>
