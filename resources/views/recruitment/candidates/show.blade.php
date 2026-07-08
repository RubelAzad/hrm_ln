@extends('layouts.admin')

@section('title', $candidate->full_name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('recruitment.candidates.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Back to Candidates</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-start gap-5">
            <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-xl font-bold text-indigo-600 shrink-0">
                {{ substr($candidate->first_name, 0, 1) }}{{ substr($candidate->last_name, 0, 1) }}
            </div>
            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $candidate->full_name }}</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $candidate->current_position ?? 'No Position' }}
                            @if ($candidate->current_company) @ {{ $candidate->current_company }} @endif
                        </p>
                    </div>
                    <div class="flex gap-2">
                        @if ($candidate->resume_path)
                            <a href="{{ route('recruitment.candidates.resume', $candidate) }}"
                               class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                                Download Resume
                            </a>
                        @endif
                        <a href="{{ route('recruitment.candidates.edit', $candidate) }}"
                           class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                            Edit
                        </a>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-600">
                    <span>{{ $candidate->email }}</span>
                    @if ($candidate->phone) <span>&middot; {{ $candidate->phone }}</span> @endif
                    @if ($candidate->experience_years) <span>&middot; {{ $candidate->experience_years }} yrs exp</span> @endif
                    <span>&middot;
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                            @if ($candidate->status === 'active') bg-green-100 text-green-700
                            @elseif ($candidate->status === 'hired') bg-emerald-100 text-emerald-700
                            @elseif ($candidate->status === 'blacklisted') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($candidate->status) }}
                        </span>
                    </span>
                </div>
                @if ($candidate->skills)
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        @foreach ($candidate->skills as $skill)
                            <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded-full">{{ $skill }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div x-data="{ activeTab: 'pipeline' }">
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex gap-6 -mb-px overflow-x-auto">
                <button @click="activeTab = 'pipeline'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'pipeline', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'pipeline' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap">Pipeline</button>
                <button @click="activeTab = 'profile'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'profile' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap">Profile</button>
                <button @click="activeTab = 'interviews'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'interviews', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'interviews' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap">Interviews</button>
                <button @click="activeTab = 'offers'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'offers', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'offers' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap">Offers</button>
                <button @click="activeTab = 'verifications'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'verifications', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'verifications' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap">Verifications</button>
                <button @click="activeTab = 'communications'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'communications', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'communications' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap">Communications</button>
                <button @click="activeTab = 'ai'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'ai', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'ai' }" class="px-1 py-4 text-sm font-medium border-b-2 whitespace-nowrap flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    AI Insights
                </button>
            </nav>
        </div>

        <div x-show="activeTab === 'pipeline'" x-cloak>
            @include('recruitment.partials.candidate-pipeline')
        </div>
        <div x-show="activeTab === 'profile'" x-cloak>
            @include('recruitment.partials.candidate-profile')
        </div>
        <div x-show="activeTab === 'interviews'" x-cloak>
            @include('recruitment.partials.candidate-interviews')
        </div>
        <div x-show="activeTab === 'offers'" x-cloak>
            @include('recruitment.partials.candidate-offers')
        </div>
        <div x-show="activeTab === 'verifications'" x-cloak>
            @include('recruitment.partials.candidate-verifications')
        </div>
        <div x-show="activeTab === 'communications'" x-cloak>
            @include('recruitment.partials.candidate-communications')
        </div>
        <div x-show="activeTab === 'ai'" x-cloak>
            @include('recruitment.partials.candidate-ai-insights')
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
