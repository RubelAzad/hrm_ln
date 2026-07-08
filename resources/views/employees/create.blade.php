@extends('layouts.admin')

@section('title', 'Add Employee')

@section('content')
    <div class="mb-6">
        <a href="{{ route('employees.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            &larr; Back to Employees
        </a>
    </div>

    <div class="max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Add New Employee</h1>

        <form action="{{ route('employees.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        @error('first_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        @error('last_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select id="gender" name="gender"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                        <select id="marital_status" name="marital_status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                            <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                    </div>
                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
                        <input type="text" id="nationality" name="nationality" value="{{ old('nationality') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Address</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea id="address" name="address" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('address') }}</textarea>
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <input type="text" id="state" name="state" value="{{ old('state') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" id="country" name="country" value="{{ old('country') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Employment Details</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <input type="text" id="department" name="department" value="{{ old('department') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="job_title" class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                        <input type="text" id="job_title" name="job_title" value="{{ old('job_title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="employment_type" class="block text-sm font-medium text-gray-700 mb-1">Employment Type</label>
                        <select id="employment_type" name="employment_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            <option value="full-time" {{ old('employment_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ old('employment_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>
                    <div>
                        <label for="supervisor_id" class="block text-sm font-medium text-gray-700 mb-1">Supervisor</label>
                        <select id="supervisor_id" name="supervisor_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">None</option>
                            @foreach ($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>{{ $supervisor->full_name }} ({{ $supervisor->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="joining_date" class="block text-sm font-medium text-gray-700 mb-1">Joining Date</label>
                        <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="confirmation_date" class="block text-sm font-medium text-gray-700 mb-1">Confirmation Date</label>
                        <input type="date" id="confirmation_date" name="confirmation_date" value="{{ old('confirmation_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                    Create Employee
                </button>
                <a href="{{ route('employees.index') }}"
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
