@extends('layouts.admin')

@section('title', 'Candidates')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Candidates</h1>
        <a href="{{ route('recruitment.candidates.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
            Add Candidate
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="GET" class="p-4 border-b border-gray-200">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[240px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search name, email, company..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500">
                </div>
                <select name="stage"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Stages</option>
                    <option value="sourced" {{ request('stage') == 'sourced' ? 'selected' : '' }}>Sourced</option>
                    <option value="applied" {{ request('stage') == 'applied' ? 'selected' : '' }}>Applied</option>
                    <option value="screening" {{ request('stage') == 'screening' ? 'selected' : '' }}>Screening</option>
                    <option value="interview" {{ request('stage') == 'interview' ? 'selected' : '' }}>Interview</option>
                    <option value="offer" {{ request('stage') == 'offer' ? 'selected' : '' }}>Offer</option>
                    <option value="hired" {{ request('stage') == 'hired' ? 'selected' : '' }}>Hired</option>
                    <option value="rejected" {{ request('stage') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <select name="status"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="passive" {{ request('status') == 'passive' ? 'selected' : '' }}>Passive</option>
                    <option value="hired" {{ request('status') == 'hired' ? 'selected' : '' }}>Hired</option>
                    <option value="blacklisted" {{ request('status') == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Filter</button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Candidate</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Current Position</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stage</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Source</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($candidates as $candidate)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('recruitment.candidates.show', $candidate) }}" class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-medium text-indigo-600">
                                        {{ substr($candidate->first_name, 0, 1) }}{{ substr($candidate->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $candidate->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $candidate->email }}</p>
                                    </div>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $candidate->current_position ?? '-' }}
                                @if ($candidate->current_company)
                                    <span class="text-xs text-gray-400">@ {{ $candidate->current_company }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php $stage = $candidate->latestStage(); @endphp
                                @if ($stage)
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                        @switch($stage->stage)
                                            @case('sourced') bg-gray-100 text-gray-700 @break
                                            @case('applied') bg-blue-100 text-blue-700 @break
                                            @case('screening') bg-yellow-100 text-yellow-700 @break
                                            @case('interview') bg-indigo-100 text-indigo-700 @break
                                            @case('offer') bg-green-100 text-green-700 @break
                                            @case('hired') bg-emerald-100 text-emerald-700 @break
                                            @default bg-red-100 text-red-700
                                        @endswitch
                                       ">
                                        {{ ucfirst($stage->stage) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">New</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($candidate->source ?? '-') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                    @if ($candidate->status === 'active') bg-green-100 text-green-700
                                    @elseif ($candidate->status === 'hired') bg-emerald-100 text-emerald-700
                                    @elseif ($candidate->status === 'blacklisted') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($candidate->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('recruitment.candidates.show', $candidate) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View</a>
                                <a href="{{ route('recruitment.candidates.edit', $candidate) }}" class="ml-3 text-gray-600 hover:text-gray-800 text-sm font-medium">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">No candidates found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">{{ $candidates->links() }}</div>
    </div>
@endsection
