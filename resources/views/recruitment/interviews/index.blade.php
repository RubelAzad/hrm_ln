@extends('layouts.admin')

@section('title', 'Interviews')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Interviews</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="GET" class="p-4 border-b border-gray-200">
            <div class="flex flex-wrap gap-4">
                <select name="status"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="rescheduled" {{ request('status') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                </select>
                <input type="date" name="from" value="{{ request('from') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                <input type="date" name="to" value="{{ request('to') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">Filter</button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Candidate</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Title</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Scheduled</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Type</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Interviewer</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($interviews as $interview)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('recruitment.candidates.show', $interview->candidate) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                    {{ $interview->candidate?->full_name ?? 'Unknown' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $interview->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $interview->scheduled_at->format('M d, Y h:i A') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $interview->interview_type) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $interview->interviewer_name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-xs font-medium rounded
                                    @if ($interview->status === 'scheduled') bg-blue-100 text-blue-700
                                    @elseif ($interview->status === 'completed') bg-green-100 text-green-700
                                    @elseif ($interview->status === 'cancelled') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700 @endif">{{ ucfirst($interview->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">No interviews found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">{{ $interviews->links() }}</div>
    </div>
@endsection
