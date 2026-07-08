@extends('layouts.admin')

@section('title', 'Edit ' . $candidate->full_name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('recruitment.candidates.show', $candidate) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Back to Profile</a>
    </div>
    <div class="max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit: {{ $candidate->full_name }}</h1>
        <form action="{{ route('recruitment.candidates.update', $candidate) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $candidate->first_name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $candidate->last_name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $candidate->email) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $candidate->phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="address" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('address', $candidate->address) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Professional Details</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Company</label>
                        <input type="text" name="current_company" value="{{ old('current_company', $candidate->current_company) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Position</label>
                        <input type="text" name="current_position" value="{{ old('current_position', $candidate->current_position) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Experience (Years)</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years', $candidate->experience_years) }}" min="0" max="70"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Education Level</label>
                        <select name="education_level"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            @foreach (['high_school' => 'High School', 'associate' => 'Associate', 'bachelor' => "Bachelor's", 'master' => "Master's", 'doctorate' => 'Doctorate'] as $val => $label)
                                <option value="{{ $val }}" {{ old('education_level', $candidate->education_level) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Skills (comma separated)</label>
                        <input type="text" name="skills" value="{{ old('skills', $candidate->skills ? implode(', ', $candidate->skills) : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Resume (leave empty to keep current)</label>
                        <input type="file" name="resume" accept=".pdf,.doc,.docx,.txt"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                        <select name="source"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            @foreach (['linkedin', 'referral', 'job_portal', 'company_website', 'recruitment_agency', 'social_media', 'other'] as $src)
                                <option value="{{ $src }}" {{ old('source', $candidate->source) == $src ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $src)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="active" {{ old('status', $candidate->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="passive" {{ old('status', $candidate->status) == 'passive' ? 'selected' : '' }}>Passive</option>
                            <option value="hired" {{ old('status', $candidate->status) == 'hired' ? 'selected' : '' }}>Hired</option>
                            <option value="blacklisted" {{ old('status', $candidate->status) == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Talent Pool</label>
                        <select name="talent_pool_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">None</option>
                            @foreach ($pools as $pool)
                                <option value="{{ $pool->id }}" {{ old('talent_pool_id', $candidate->talent_pool_id) == $pool->id ? 'selected' : '' }}>{{ $pool->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('notes', $candidate->notes) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Update Candidate</button>
                <a href="{{ route('recruitment.candidates.show', $candidate) }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
@endsection
