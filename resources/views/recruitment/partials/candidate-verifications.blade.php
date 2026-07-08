<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Background Verifications</h3>
        <button onclick="document.getElementById('verificationForm').classList.toggle('hidden')"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">+ Initiate Check</button>
    </div>

    <form id="verificationForm" method="POST" action="{{ route('recruitment.verifications.store', $candidate) }}" class="hidden mb-6 p-4 border border-gray-200 rounded-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                <select name="type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="education">Education</option>
                    <option value="employment">Employment</option>
                    <option value="identity">Identity</option>
                    <option value="criminal">Criminal</option>
                    <option value="reference">Reference</option>
                    <option value="drug_test">Drug Test</option>
                    <option value="credit_check">Credit Check</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                <input type="text" name="vendor"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">Initiate</button>
    </form>

    @if ($candidate->verifications->count() > 0)
        <div class="space-y-3">
            @foreach ($candidate->verifications as $verification)
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $verification->type) }}</h4>
                                <span class="px-1.5 py-0.5 text-xs font-medium rounded
                                    @if ($verification->status === 'pending') bg-yellow-100 text-yellow-700
                                    @elseif ($verification->status === 'in_progress') bg-blue-100 text-blue-700
                                    @elseif ($verification->status === 'cleared') bg-green-100 text-green-700
                                    @elseif ($verification->status === 'flagged') bg-orange-100 text-orange-700
                                    @else bg-red-100 text-red-700 @endif">{{ ucfirst(str_replace('_', ' ', $verification->status)) }}</span>
                            </div>
                            @if ($verification->vendor)<p class="text-xs text-gray-500 mt-1">Vendor: {{ $verification->vendor }}</p>@endif
                            @if ($verification->initiated_at)<p class="text-xs text-gray-500">Initiated: {{ $verification->initiated_at->format('M d, Y') }}</p>@endif
                            @if ($verification->completed_at)<p class="text-xs text-gray-500">Completed: {{ $verification->completed_at->format('M d, Y') }}</p>@endif
                            @if ($verification->report_summary)<p class="text-sm text-gray-600 mt-1">{{ $verification->report_summary }}</p>@endif
                        </div>
                        @if ($verification->status === 'pending' || $verification->status === 'in_progress')
                            <button onclick="document.getElementById('updateVer{{ $verification->id }}').classList.toggle('hidden')"
                                    class="text-sm text-indigo-600 hover:text-indigo-800 shrink-0">Update</button>
                        @endif
                    </div>

                    @if ($verification->status === 'pending' || $verification->status === 'in_progress')
                        <form id="updateVer{{ $verification->id }}" method="POST" action="{{ route('recruitment.verifications.update', $verification) }}"
                              enctype="multipart/form-data" class="hidden mt-4 p-3 border border-gray-200 rounded-lg">
                            @csrf
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Status *</label>
                                    <select name="status" required
                                            class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                                        <option value="in_progress">In Progress</option>
                                        <option value="cleared">Cleared</option>
                                        <option value="flagged">Flagged</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Completed At</label>
                                    <input type="date" name="completed_at"
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Report Summary</label>
                                    <textarea name="report_summary" rows="2"
                                              class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Upload Report</label>
                                    <input type="file" name="report" accept=".pdf,.doc,.docx,.jpg,.png"
                                           class="w-full text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                            </div>
                            <button type="submit" class="mt-3 px-3 py-1.5 bg-indigo-600 text-white rounded text-sm font-medium hover:bg-indigo-700">Update</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No background verifications initiated yet.</p>
    @endif
</div>
