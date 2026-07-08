@extends('layouts.admin')

@section('title', 'Create Job Posting')

@section('content')
    <div class="mb-6">
        <a href="{{ route('recruitment.jobs.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Back to Jobs</a>
    </div>
    <div class="max-w-4xl">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Create Job Posting</h1>
        <form action="{{ route('recruitment.jobs.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Job Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <input type="text" name="department" value="{{ old('department') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Employment Type</label>
                        <select name="employment_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            <option value="full-time" {{ old('employment_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ old('employment_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="temporary" {{ old('employment_type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                            <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Experience Level</label>
                        <select name="experience_level"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            <option value="entry" {{ old('experience_level') == 'entry' ? 'selected' : '' }}>Entry Level</option>
                            <option value="mid" {{ old('experience_level') == 'mid' ? 'selected' : '' }}>Mid Level</option>
                            <option value="senior" {{ old('experience_level') == 'senior' ? 'selected' : '' }}>Senior</option>
                            <option value="lead" {{ old('experience_level') == 'lead' ? 'selected' : '' }}>Lead</option>
                            <option value="executive" {{ old('experience_level') == 'executive' ? 'selected' : '' }}>Executive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Salary Min</label>
                        <input type="number" step="0.01" name="salary_min" value="{{ old('salary_min') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Salary Max</label>
                        <input type="number" step="0.01" name="salary_max" value="{{ old('salary_max') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <input type="text" name="currency" value="{{ old('currency', 'USD') }}" maxlength="3"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vacancies</label>
                        <input type="number" name="vacancies" value="{{ old('vacancies', 1) }}" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Closing Date</label>
                        <input type="date" name="closing_at" value="{{ old('closing_at') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Requirements</label>
                        <textarea name="requirements" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('requirements') }}</textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Responsibilities</label>
                        <textarea name="responsibilities" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('responsibilities') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Create Job</button>
                <a href="{{ route('recruitment.jobs.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
@endsection
