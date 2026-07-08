@extends('layouts.admin')

@section('title', 'Edit ' . $job->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('recruitment.jobs.show', $job) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Back to Job</a>
    </div>
    <div class="max-w-4xl">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit: {{ $job->title }}</h1>
        <form action="{{ route('recruitment.jobs.update', $job) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Job Title *</label>
                        <input type="text" name="title" value="{{ old('title', $job->title) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <input type="text" name="department" value="{{ old('department', $job->department) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" value="{{ old('location', $job->location) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Employment Type</label>
                        <select name="employment_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            @foreach (['full-time', 'part-time', 'contract', 'temporary', 'internship'] as $type)
                                <option value="{{ $type }}" {{ old('employment_type', $job->employment_type) == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('-', ' ', $type)) }}</option>
                            @endforeach
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Experience Level</label>
                        <select name="experience_level"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            @foreach (['entry' => 'Entry', 'mid' => 'Mid', 'senior' => 'Senior', 'lead' => 'Lead', 'executive' => 'Executive'] as $val => $lbl)
                                <option value="{{ $val }}" {{ old('experience_level', $job->experience_level) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Salary Min</label>
                        <input type="number" step="0.01" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Salary Max</label>
                        <input type="number" step="0.01" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <input type="text" name="currency" value="{{ old('currency', $job->currency) }}" maxlength="3"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Vacancies</label>
                        <input type="number" name="vacancies" value="{{ old('vacancies', $job->vacancies) }}" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            @foreach (['draft', 'published', 'closed', 'filled'] as $st)
                                <option value="{{ $st }}" {{ old('status', $job->status) == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Closing Date</label>
                        <input type="date" name="closing_at" value="{{ old('closing_at', $job->closing_at?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('description', $job->description) }}</textarea></div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Requirements</label>
                        <textarea name="requirements" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('requirements', $job->requirements) }}</textarea></div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Responsibilities</label>
                        <textarea name="responsibilities" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('responsibilities', $job->responsibilities) }}</textarea></div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Update Job</button>
                <a href="{{ route('recruitment.jobs.show', $job) }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
@endsection
