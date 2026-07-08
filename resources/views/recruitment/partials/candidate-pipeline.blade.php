<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Pipeline</h3>
        <button onclick="document.getElementById('advanceForm').classList.toggle('hidden')"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">+ Move Stage</button>
    </div>

    <form id="advanceForm" method="POST" action="{{ route('recruitment.pipeline.advance', $candidate) }}" class="hidden mb-6 p-4 border border-gray-200 rounded-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job *</label>
                <select name="job_posting_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    @foreach ($jobs as $job)
                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stage *</label>
                <select name="stage" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="sourced">Sourced</option>
                    <option value="applied">Applied</option>
                    <option value="screening">Screening</option>
                    <option value="interview">Interview</option>
                    <option value="offer">Offer</option>
                    <option value="hired">Hired</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">Advance</button>
    </form>

    @if ($candidate->pipelineStages->count() > 0)
        <div class="space-y-4">
            @php $order = 1; @endphp
            @foreach ($candidate->pipelineStages->sortBy('stage_order') as $stage)
                <div class="flex items-start gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                            @if ($stage->stage === 'hired') bg-emerald-100 text-emerald-600
                            @elseif ($stage->stage === 'rejected') bg-red-100 text-red-600
                            @else bg-indigo-100 text-indigo-600 @endif">
                            {{ $order }}
                        </div>
                        @if (!$loop->last)<div class="w-0.5 flex-1 bg-gray-200 mt-1"></div>@endif
                    </div>
                    <div class="flex-1 pb-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 capitalize">{{ $stage->stage }}</p>
                                <p class="text-xs text-gray-500">{{ $stage->jobPosting?->title }} &middot; {{ $stage->stage_changed_at?->diffForHumans() ?? 'N/A' }}</p>
                                @if ($stage->notes)
                                    <p class="text-sm text-gray-600 mt-1">{{ $stage->notes }}</p>
                                @endif
                                @if ($stage->createdBy)
                                    <p class="text-xs text-gray-400 mt-0.5">by {{ $stage->createdBy->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @php $order++; @endphp
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No pipeline stages yet. Add the candidate to a job pipeline.</p>
    @endif
</div>
