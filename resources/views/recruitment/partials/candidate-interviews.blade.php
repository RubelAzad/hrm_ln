<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Interviews</h3>
        <button onclick="document.getElementById('scheduleForm').classList.toggle('hidden')"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">+ Schedule</button>
    </div>

    <form id="scheduleForm" method="POST" action="{{ route('recruitment.interviews.store', $candidate) }}" class="hidden mb-6 p-4 border border-gray-200 rounded-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input type="text" name="title" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                <select name="interview_type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="phone">Phone</option>
                    <option value="video">Video</option>
                    <option value="in_person">In Person</option>
                    <option value="technical">Technical</option>
                    <option value="panel">Panel</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled At *</label>
                <input type="datetime-local" name="scheduled_at" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration (min)</label>
                <input type="number" name="duration_minutes" value="60" min="15" max="480"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Interviewer Name</label>
                <input type="text" name="interviewer_name"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Interviewer Email</label>
                <input type="email" name="interviewer_email"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Location / Link</label>
                <input type="text" name="location_or_link"
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">Schedule Interview</button>
    </form>

    @if ($candidate->interviews->count() > 0)
        <div class="space-y-3">
            @foreach ($candidate->interviews->sortByDesc('scheduled_at') as $interview)
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-medium text-gray-900">{{ $interview->title }}</h4>
                                <span class="px-1.5 py-0.5 text-xs font-medium rounded
                                    @if ($interview->status === 'scheduled') bg-blue-100 text-blue-700
                                    @elseif ($interview->status === 'completed') bg-green-100 text-green-700
                                    @elseif ($interview->status === 'cancelled') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700 @endif">{{ ucfirst($interview->status) }}</span>
                                <span class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', $interview->interview_type) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $interview->scheduled_at->format('M d, Y h:i A') }} ({{ $interview->duration_minutes }} min)</p>
                            @if ($interview->interviewer_name)<p class="text-xs text-gray-500">Interviewer: {{ $interview->interviewer_name }}</p>@endif
                            @if ($interview->meeting_link)<a href="{{ $interview->meeting_link }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-800">Join Meeting</a>@endif
                            @if ($interview->feedback)
                                <div class="mt-2 p-2 bg-gray-50 rounded text-sm text-gray-600">
                                    <p class="text-xs font-medium text-gray-500 mb-1">Feedback:</p>
                                    {{ $interview->feedback }}
                                    @if ($interview->rating)
                                        <p class="text-xs text-gray-500 mt-1">Rating: {{ $interview->rating }}/5</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @if ($interview->status === 'scheduled')
                            <button onclick="document.getElementById('editInterview{{ $interview->id }}').classList.toggle('hidden')"
                                    class="text-sm text-indigo-600 hover:text-indigo-800 shrink-0">Edit</button>
                        @endif
                    </div>

                    @if ($interview->status === 'scheduled')
                        <form id="editInterview{{ $interview->id }}" method="POST" action="{{ route('recruitment.interviews.update', $interview) }}"
                              class="hidden mt-4 p-3 border border-gray-200 rounded-lg">
                            @csrf
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status"
                                            class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                                        <option value="scheduled">Scheduled</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                        <option value="rescheduled">Rescheduled</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Rating (1-5)</label>
                                    <input type="number" name="rating" min="1" max="5"
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Feedback</label>
                                <textarea name="feedback" rows="2"
                                          class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>
                            <button type="submit" class="mt-3 px-3 py-1.5 bg-indigo-600 text-white rounded text-sm font-medium hover:bg-indigo-700">Update</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No interviews scheduled yet.</p>
    @endif
</div>
