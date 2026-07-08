<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Offer Letters</h3>
        <button onclick="document.getElementById('offerForm').classList.toggle('hidden')"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">+ Create Offer</button>
    </div>

    <form id="offerForm" method="POST" action="{{ route('recruitment.offers.store', $candidate) }}" class="hidden mb-6 p-4 border border-gray-200 rounded-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Offer Date *</label>
                <input type="date" name="offer_date" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date *</label>
                <input type="date" name="expiry_date" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Offer Amount</label>
                <input type="number" step="0.01" name="offer_amount"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                <select name="currency"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                </select>
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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="draft">Draft</option>
                    <option value="sent">Sent</option>
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                <textarea name="terms" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Offer Letter Content (HTML or plain text)</label>
                <textarea name="offer_letter_content" rows="6"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 font-mono"></textarea>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">Create Offer</button>
    </form>

    @if ($candidate->offerLetters->count() > 0)
        <div class="space-y-3">
            @foreach ($candidate->offerLetters->sortByDesc('created_at') as $offer)
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-medium text-gray-900">Offer #{{ $offer->id }}</h4>
                                <span class="px-1.5 py-0.5 text-xs font-medium rounded
                                    @if ($offer->status === 'draft') bg-gray-100 text-gray-700
                                    @elseif ($offer->status === 'sent') bg-blue-100 text-blue-700
                                    @elseif ($offer->status === 'accepted') bg-green-100 text-green-700
                                    @elseif ($offer->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700 @endif">{{ ucfirst($offer->status) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Offer Date: {{ $offer->offer_date->format('M d, Y') }} &middot; Expires: {{ $offer->expiry_date->format('M d, Y') }}</p>
                            @if ($offer->offer_amount)
                                <p class="text-xs text-gray-500">{{ $offer->currency }} {{ number_format($offer->offer_amount, 2) }}</p>
                            @endif
                            @if ($offer->notes)<p class="text-sm text-gray-600 mt-1">{{ $offer->notes }}</p>@endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No offer letters created yet.</p>
    @endif
</div>
