<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Details</h3>
    <dl class="grid grid-cols-2 gap-x-6 gap-y-4">
        <div><dt class="text-sm font-medium text-gray-500">Full Name</dt><dd class="mt-1 text-sm text-gray-900">{{ $candidate->full_name }}</dd></div>
        <div><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="mt-1 text-sm text-gray-900">{{ $candidate->email }}</dd></div>
        <div><dt class="text-sm font-medium text-gray-500">Phone</dt><dd class="mt-1 text-sm text-gray-900">{{ $candidate->phone ?? '-' }}</dd></div>
        <div><dt class="text-sm font-medium text-gray-500">Address</dt><dd class="mt-1 text-sm text-gray-900">{{ $candidate->address ?? '-' }}</dd></div>
        <div><dt class="text-sm font-medium text-gray-500">Current Company</dt><dd class="mt-1 text-sm text-gray-900">{{ $candidate->current_company ?? '-' }}</dd></div>
        <div><dt class="text-sm font-medium text-gray-500">Current Position</dt><dd class="mt-1 text-sm text-gray-900">{{ $candidate->current_position ?? '-' }}</dd></div>
        <div><dt class="text-sm font-medium text-gray-500">Experience</dt><dd class="mt-1 text-sm text-gray-900">{{ $candidate->experience_years ? $candidate->experience_years . ' years' : '-' }}</dd></div>
        <div><dt class="text-sm font-medium text-gray-500">Education</dt><dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $candidate->education_level ?? '-')) }}</dd></div>
        <div><dt class="text-sm font-medium text-gray-500">Source</dt><dd class="mt-1 text-sm text-gray-900">{{ ucfirst($candidate->source ?? '-') }}</dd></div>
        <div><dt class="text-sm font-medium text-gray-500">Talent Pool</dt><dd class="mt-1 text-sm text-gray-900">{{ $candidate->talentPool?->name ?? '-' }}</dd></div>
        <div><dt class="text-sm font-medium text-gray-500">Status</dt><dd class="mt-1"><span class="px-2 py-0.5 text-xs font-medium rounded-full @if ($candidate->status === 'active') bg-green-100 text-green-700 @elseif ($candidate->status === 'hired') bg-emerald-100 text-emerald-700 @else bg-gray-100 text-gray-700 @endif">{{ ucfirst($candidate->status) }}</span></dd></div>
    </dl>
    @if ($candidate->notes)
        <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-2">Notes</h3>
        <p class="text-sm text-gray-600">{{ $candidate->notes }}</p>
    @endif
</div>
