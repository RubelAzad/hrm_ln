<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Employment History</h3>
        <button onclick="document.getElementById('addHistoryForm').classList.toggle('hidden')"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            + Add History
        </button>
    </div>

    <form id="addHistoryForm" method="POST" action="{{ route('employees.histories.store', $employee) }}" class="hidden mb-6 p-4 border border-gray-200 rounded-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                <input type="text" name="company_name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Title *</label>
                <input type="text" name="job_title" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                <input type="date" name="start_date" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="flex items-center">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_current" value="1"
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                           onchange="document.querySelector('[name=end_date]').disabled = this.checked">
                    <span class="text-sm text-gray-700">Current Position</span>
                </label>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                Save History
            </button>
        </div>
    </form>

    @if ($employee->histories->count() > 0)
        <div class="relative">
            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
            <div class="space-y-6">
                @foreach ($employee->histories->sortByDesc('start_date') as $history)
                    <div class="relative pl-10">
                        <div class="absolute left-2.5 top-1.5 w-3 h-3 rounded-full border-2 border-indigo-500 bg-white"></div>
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $history->job_title }}</h4>
                                <p class="text-sm text-gray-600">{{ $history->company_name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $history->start_date->format('M Y') }} -
                                    {{ $history->is_current ? 'Present' : $history->end_date?->format('M Y') }}
                                    @if ($history->is_current)
                                        <span class="px-1.5 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded ml-1">Current</span>
                                    @endif
                                </p>
                                @if ($history->description)
                                    <p class="text-sm text-gray-500 mt-1">{{ $history->description }}</p>
                                @endif
                            </div>
                            <form action="{{ route('employees.histories.destroy', [$employee, $history]) }}" method="POST"
                                  onsubmit="return confirm('Delete this history record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <p class="text-sm text-gray-500">No employment history added yet.</p>
    @endif
</div>
