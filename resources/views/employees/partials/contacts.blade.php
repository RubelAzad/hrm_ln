<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Emergency Contacts</h3>
        <button onclick="document.getElementById('addContactForm').classList.toggle('hidden')"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            + Add Contact
        </button>
    </div>

    <form id="addContactForm" method="POST" action="{{ route('employees.contacts.store', $employee) }}" class="hidden mb-6 p-4 border border-gray-200 rounded-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                <input type="text" name="name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Relationship *</label>
                <input type="text" name="relationship" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                <input type="text" name="phone" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div class="flex items-center">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_primary" value="1"
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-gray-700">Primary Contact</span>
                </label>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                Save Contact
            </button>
        </div>
    </form>

    @if ($employee->contacts->count() > 0)
        <div class="space-y-3">
            @foreach ($employee->contacts as $contact)
                <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-medium text-gray-900">{{ $contact->name }}</h4>
                            @if ($contact->is_primary)
                                <span class="px-2 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-700 rounded-full">Primary</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $contact->relationship }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $contact->phone }}</p>
                        @if ($contact->email)
                            <p class="text-sm text-gray-600">{{ $contact->email }}</p>
                        @endif
                        @if ($contact->address)
                            <p class="text-sm text-gray-600">{{ $contact->address }}</p>
                        @endif
                    </div>
                    <form action="{{ route('employees.contacts.destroy', [$employee, $contact]) }}" method="POST"
                          onsubmit="return confirm('Delete this contact?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Remove</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No emergency contacts added yet.</p>
    @endif
</div>
