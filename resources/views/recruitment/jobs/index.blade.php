@extends('layouts.admin')

@section('title', 'Job Postings')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Job Postings</h1>
        <a href="{{ route('recruitment.jobs.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Post Job</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="GET" class="p-4 border-b border-gray-200">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[240px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, department, location..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500">
                </div>
                <select name="status"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="filled" {{ request('status') == 'filled' ? 'selected' : '' }}>Filled</option>
                </select>
                <select name="department"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Departments</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">Filter</button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Title</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Department</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Location</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Type</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Candidates</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($jobs as $job)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('recruitment.jobs.show', $job) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">{{ $job->title }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $job->department ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $job->location ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $job->employment_type ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $job->pipeline_stages_count ?? 0 }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-xs font-medium rounded
                                    @if ($job->status === 'published') bg-green-100 text-green-700
                                    @elseif ($job->status === 'draft') bg-gray-100 text-gray-700
                                    @elseif ($job->status === 'closed') bg-yellow-100 text-yellow-700
                                    @else bg-blue-100 text-blue-700 @endif">{{ ucfirst($job->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('recruitment.jobs.show', $job) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View</a>
                                <a href="{{ route('recruitment.jobs.edit', $job) }}" class="ml-3 text-gray-600 hover:text-gray-800 text-sm font-medium">Edit</a>
                                <form action="{{ route('recruitment.jobs.destroy', $job) }}" method="POST" class="inline" onsubmit="return confirm('Delete this job posting?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ml-3 text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">No job postings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">{{ $jobs->links() }}</div>
    </div>
@endsection
