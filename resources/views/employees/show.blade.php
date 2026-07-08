@extends('layouts.admin')

@section('title', $employee->full_name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('employees.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            &larr; Back to Employees
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-start gap-6">
            <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold text-indigo-600 shrink-0">
                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
            </div>
            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $employee->full_name }}</h1>
                        <p class="text-sm text-gray-500 mt-1">{{ $employee->employee_id }} &middot; {{ $employee->job_title ?? 'No Title' }} &middot; {{ $employee->department ?? 'No Department' }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        @if ($employee->status === 'active') bg-green-100 text-green-800
                        @elseif ($employee->status === 'inactive') bg-yellow-100 text-yellow-800
                        @elseif ($employee->status === 'suspended') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($employee->status) }}
                    </span>
                </div>
                <div class="flex items-center gap-6 mt-4 text-sm text-gray-600">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $employee->email }}
                    </span>
                    @if ($employee->phone)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $employee->phone }}
                        </span>
                    @endif
                    @if ($employee->joining_date)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Joined {{ $employee->joining_date->format('M d, Y') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('employees.edit', $employee) }}"
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    Edit
                </a>
            </div>
        </div>
    </div>

    <div x-data="{ activeTab: 'profile' }">
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex gap-6 -mb-px overflow-x-auto">
                <button @click="activeTab = 'profile'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'profile' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                    Profile
                </button>
                <button @click="activeTab = 'contacts'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'contacts', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'contacts' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                    Emergency Contacts
                </button>
                <button @click="activeTab = 'history'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'history', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'history' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                    Employment History
                </button>
                <button @click="activeTab = 'skills'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'skills', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'skills' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                    Skills & Certifications
                </button>
                <button @click="activeTab = 'documents'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'documents', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'documents' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                    Documents
                </button>
                <button @click="activeTab = 'assets'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'assets', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'assets' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                    Assets
                </button>
                <button @click="activeTab = 'exit'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'exit', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'exit' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                    Exit Process
                </button>
            </nav>
        </div>

        <div x-show="activeTab === 'profile'" x-cloak>
            @include('employees.partials.profile')
        </div>
        <div x-show="activeTab === 'contacts'" x-cloak>
            @include('employees.partials.contacts')
        </div>
        <div x-show="activeTab === 'history'" x-cloak>
            @include('employees.partials.history')
        </div>
        <div x-show="activeTab === 'skills'" x-cloak>
            @include('employees.partials.skills')
        </div>
        <div x-show="activeTab === 'documents'" x-cloak>
            @include('employees.partials.documents')
        </div>
        <div x-show="activeTab === 'assets'" x-cloak>
            @include('employees.partials.assets')
        </div>
        <div x-show="activeTab === 'exit'" x-cloak>
            @include('employees.partials.exit')
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
