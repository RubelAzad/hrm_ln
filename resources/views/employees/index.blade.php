@extends('layouts.admin')

@section('title', 'Employees')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Employees</h1>
        <a href="{{ route('employees.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Employee
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="GET" class="p-4 border-b border-gray-200">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[240px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by name, ID, email, or department..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <select name="department"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Departments</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
                <select name="status"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                    Filter
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee ID</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Supervisor</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($employees as $employee)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-indigo-600">
                                {{ $employee->employee_id }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('employees.show', $employee) }}" class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-medium text-indigo-600">
                                        {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $employee->full_name }}</span>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $employee->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $employee->department ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $employee->supervisor?->full_name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($employee->status === 'active') bg-green-100 text-green-800
                                    @elseif ($employee->status === 'inactive') bg-yellow-100 text-yellow-800
                                    @elseif ($employee->status === 'suspended') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('employees.show', $employee) }}"
                                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View</a>
                                <a href="{{ route('employees.edit', $employee) }}"
                                   class="ml-3 text-gray-600 hover:text-gray-800 text-sm font-medium">Edit</a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ml-3 text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                No employees found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $employees->links() }}
        </div>
    </div>
@endsection
