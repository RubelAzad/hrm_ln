<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Exit Process</h3>
        @if ($employee->exitRecord->count() === 0 && $employee->status !== 'terminated')
            <button onclick="document.getElementById('initExitForm').classList.toggle('hidden')"
                    class="text-sm font-medium text-red-600 hover:text-red-800">
                Initiate Exit
            </button>
        @endif
    </div>

    @if ($employee->exitRecord->count() === 0)
        @if ($employee->status !== 'terminated')
            <form id="initExitForm" method="POST" action="{{ route('employees.exit.init', $employee) }}" class="hidden mb-6 p-4 border border-red-200 rounded-lg">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Exit Type *</label>
                        <select name="exit_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="resignation">Resignation</option>
                            <option value="termination">Termination</option>
                            <option value="retirement">Retirement</option>
                            <option value="end_of_contract">End of Contract</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notice Date *</label>
                        <input type="date" name="notice_date" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Exit Date *</label>
                        <input type="date" name="exit_date" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-center pt-6">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_voluntary" value="1" checked
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Voluntary Exit</span>
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                        <textarea name="reason" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors"
                            onclick="return confirm('Are you sure you want to initiate the exit process for this employee? This will set their status to inactive.')">
                        Initiate Exit
                    </button>
                </div>
            </form>
        @else
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">This employee has already been terminated.</p>
            </div>
        @endif
    @else
        @php $exit = $employee->exitRecord->first(); @endphp
        <div class="space-y-4">
            <div class="p-4 border border-gray-200 rounded-lg">
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Exit Type</dt>
                        <dd class="text-sm text-gray-900 mt-0.5">{{ ucfirst(str_replace('_', ' ', $exit->exit_type)) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Status</dt>
                        <dd class="mt-0.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                @if ($exit->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif ($exit->status === 'approved') bg-green-100 text-green-800
                                @elseif ($exit->status === 'completed') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($exit->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Notice Date</dt>
                        <dd class="text-sm text-gray-900 mt-0.5">{{ $exit->notice_date->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Exit Date</dt>
                        <dd class="text-sm text-gray-900 mt-0.5">{{ $exit->exit_date->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Voluntary</dt>
                        <dd class="text-sm text-gray-900 mt-0.5">{{ $exit->is_voluntary ? 'Yes' : 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Clearance Status</dt>
                        <dd class="mt-0.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                @if ($exit->clearance_status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif ($exit->clearance_status === 'partial') bg-orange-100 text-orange-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($exit->clearance_status) }}
                            </span>
                        </dd>
                    </div>
                    @if ($exit->reason)
                        <div class="col-span-2">
                            <dt class="text-xs font-medium text-gray-500">Reason</dt>
                            <dd class="text-sm text-gray-900 mt-0.5">{{ $exit->reason }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <details class="border border-gray-200 rounded-lg">
                <summary class="px-4 py-3 cursor-pointer text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg">
                    Update Exit Record
                </summary>
                <form method="POST" action="{{ route('employees.exit.update', [$employee, $exit]) }}" class="p-4 border-t border-gray-200">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Settlement Amount</label>
                            <input type="number" step="0.01" name="settlement_amount" value="{{ old('settlement_amount', $exit->settlement_amount) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Exit Interview Date</label>
                            <input type="date" name="exit_interview_date" value="{{ old('exit_interview_date', $exit->exit_interview_date?->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Clearance Status *</label>
                            <select name="clearance_status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                                <option value="pending" {{ ($exit->clearance_status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="partial" {{ ($exit->clearance_status ?? '') == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="completed" {{ ($exit->clearance_status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                                <option value="pending" {{ $exit->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $exit->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $exit->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="completed" {{ $exit->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 pt-6">
                                <input type="checkbox" name="rehire_eligible" value="1" {{ $exit->rehire_eligible ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Rehire Eligible</span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Clearance Notes</label>
                            <input type="text" name="clearance_notes" value="{{ old('clearance_notes', $exit->clearance_notes) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Exit Interview Notes</label>
                            <textarea name="exit_interview_notes" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('exit_interview_notes', $exit->exit_interview_notes) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                            Update Exit Record
                        </button>
                    </div>
                </form>
            </details>
        </div>
    @endif
</div>
