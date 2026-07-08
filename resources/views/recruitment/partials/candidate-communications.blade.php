<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Communications Log</h3>
        <button onclick="document.getElementById('commForm').classList.toggle('hidden')"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">+ Log Communication</button>
    </div>

    <form id="commForm" method="POST" action="{{ route('recruitment.communications.store', $candidate) }}" class="hidden mb-6 p-4 border border-gray-200 rounded-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                <select name="type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="email">Email</option>
                    <option value="phone">Phone</option>
                    <option value="sms">SMS</option>
                    <option value="in_person">In Person</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Direction</label>
                <select name="direction"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="outbound">Outbound (To Candidate)</option>
                    <option value="inbound">Inbound (From Candidate)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <input type="text" name="subject"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Posting</label>
                <select name="job_posting_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="">None</option>
                    @foreach ($jobs as $job)
                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                <textarea name="message" rows="3" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">Log Communication</button>
    </form>

    @if ($candidate->communications->count() > 0)
        <div class="space-y-3">
            @foreach ($candidate->communications->sortByDesc('sent_at') as $comm)
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="px-1.5 py-0.5 text-xs font-medium rounded
                                    @if ($comm->type === 'email') bg-blue-100 text-blue-700
                                    @elseif ($comm->type === 'phone') bg-green-100 text-green-700
                                    @elseif ($comm->type === 'sms') bg-yellow-100 text-yellow-700
                                    @else bg-gray-100 text-gray-700 @endif">{{ ucfirst($comm->type) }}</span>
                                <span class="text-xs text-gray-400">{{ ucfirst($comm->direction) }}</span>
                                @if ($comm->subject)
                                    <span class="text-sm font-medium text-gray-900">{{ $comm->subject }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-2 whitespace-pre-wrap">{{ $comm->message }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $comm->sent_at?->format('M d, Y h:i A') ?? 'N/A' }}
                                @if ($comm->sentBy) by {{ $comm->sentBy->name }} @endif
                                @if ($comm->jobPosting) &middot; Re: {{ $comm->jobPosting->title }} @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No communications logged yet.</p>
    @endif
</div>
