@extends('layouts.admin')

@section('title', 'Organization Chart')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Organization Chart</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        @if ($employees->count() > 0)
            <div class="flex flex-col items-center">
                @foreach ($employees as $topLevel)
                    <div class="w-full flex flex-col items-center">
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-xl font-bold text-indigo-600 mx-auto">
                                {{ substr($topLevel->first_name, 0, 1) }}{{ substr($topLevel->last_name, 0, 1) }}
                            </div>
                            <p class="mt-2 text-sm font-medium text-gray-900">{{ $topLevel->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $topLevel->job_title ?? 'No Title' }}</p>
                            <p class="text-xs text-gray-400">{{ $topLevel->employee_id }}</p>
                        </div>

                        @if ($topLevel->subordinates->count() > 0)
                            <div class="mt-1 w-0.5 h-8 bg-gray-300"></div>
                            <div class="w-full border-t border-gray-300 relative" style="height: 2px;">
                                <div class="absolute -top-1 left-0 right-0 flex justify-around">
                                    @foreach ($topLevel->subordinates as $sub)
                                        <div class="w-0.5 h-8 bg-gray-300"></div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="w-full flex justify-around mt-8">
                                @foreach ($topLevel->subordinates as $sub)
                                    <div class="text-center">
                                        <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center text-lg font-bold text-blue-600 mx-auto">
                                            {{ substr($sub->first_name, 0, 1) }}{{ substr($sub->last_name, 0, 1) }}
                                        </div>
                                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $sub->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $sub->job_title ?? 'No Title' }}</p>
                                        <p class="text-xs text-gray-400">{{ $sub->employee_id }}</p>

                                        @if ($sub->subordinates->count() > 0)
                                            <div class="mt-1 w-0.5 h-6 bg-gray-300 mx-auto"></div>
                                            <div class="border-t border-gray-300 relative" style="width: {{ $sub->subordinates->count() * 160 }}px; height: 2px; margin: 0 auto;">
                                                <div class="absolute -top-1 left-0 right-0 flex justify-around px-8">
                                                    @foreach ($sub->subordinates as $sub2)
                                                        <div class="w-0.5 h-6 bg-gray-300"></div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="flex justify-around gap-1 mt-6" style="width: {{ $sub->subordinates->count() * 160 }}px;">
                                                @foreach ($sub->subordinates as $sub2)
                                                    <div class="text-center">
                                                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-base font-bold text-green-600 mx-auto">
                                                            {{ substr($sub2->first_name, 0, 1) }}{{ substr($sub2->last_name, 0, 1) }}
                                                        </div>
                                                        <p class="mt-1 text-xs font-medium text-gray-900">{{ $sub2->full_name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $sub2->job_title ?? 'No Title' }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-sm text-gray-500">No employees found. Add employees with supervisors to build the organization chart.</p>
                <a href="{{ route('employees.create') }}"
                   class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                    Add Employee
                </a>
            </div>
        @endif
    </div>
@endsection
