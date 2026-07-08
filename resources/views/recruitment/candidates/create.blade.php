@extends('layouts.admin')

@section('title', 'Add Candidate')

@section('content')
    <div class="mb-6">
        <a href="{{ route('recruitment.candidates.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Back to Candidates</a>
    </div>
    <div class="max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Add New Candidate</h1>
        <form action="{{ route('recruitment.candidates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        @error('first_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        @error('last_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="address" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Professional Details</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Company</label>
                        <input type="text" name="current_company" value="{{ old('current_company') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Position</label>
                        <input type="text" name="current_position" value="{{ old('current_position') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Experience (Years)</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years') }}" min="0" max="70"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Education Level</label>
                        <select name="education_level"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            <option value="high_school" {{ old('education_level') == 'high_school' ? 'selected' : '' }}>High School</option>
                            <option value="associate" {{ old('education_level') == 'associate' ? 'selected' : '' }}>Associate</option>
                            <option value="bachelor" {{ old('education_level') == 'bachelor' ? 'selected' : '' }}>Bachelor's</option>
                            <option value="master" {{ old('education_level') == 'master' ? 'selected' : '' }}>Master's</option>
                            <option value="doctorate" {{ old('education_level') == 'doctorate' ? 'selected' : '' }}>Doctorate</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Skills (comma separated)</label>
                        <input type="text" name="skills" value="{{ old('skills') }}" placeholder="PHP, Laravel, React, MySQL"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Resume (PDF, DOC, max 5MB)</label>
                        <input type="file" name="resume" accept=".pdf,.doc,.docx,.txt"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('resume') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                        <select name="source"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            <option value="linkedin" {{ old('source') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                            <option value="referral" {{ old('source') == 'referral' ? 'selected' : '' }}>Referral</option>
                            <option value="job_portal" {{ old('source') == 'job_portal' ? 'selected' : '' }}>Job Portal</option>
                            <option value="company_website" {{ old('source') == 'company_website' ? 'selected' : '' }}>Company Website</option>
                            <option value="recruitment_agency" {{ old('source') == 'recruitment_agency' ? 'selected' : '' }}>Recruitment Agency</option>
                            <option value="social_media" {{ old('source') == 'social_media' ? 'selected' : '' }}>Social Media</option>
                            <option value="other" {{ old('source') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pipeline & Pool</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Apply to Job</label>
                        <select name="job_posting_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select Job</option>
                            @foreach ($jobs as $job)
                                <option value="{{ $job->id }}" {{ old('job_posting_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Talent Pool</label>
                        <select name="talent_pool_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">None</option>
                            @foreach ($pools as $pool)
                                <option value="{{ $pool->id }}" {{ old('talent_pool_id') == $pool->id ? 'selected' : '' }}>{{ $pool->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Add Candidate</button>
                <a href="{{ route('recruitment.candidates.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
@endsection
