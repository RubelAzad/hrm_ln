<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Documents</h3>
        <button onclick="document.getElementById('addDocumentForm').classList.toggle('hidden')"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            + Upload Document
        </button>
    </div>

    <form id="addDocumentForm" method="POST" action="{{ route('employees.documents.store', $employee) }}" enctype="multipart/form-data" class="hidden mb-6 p-4 border border-gray-200 rounded-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Document Type *</label>
                <select name="document_type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="offer_letter">Offer Letter</option>
                    <option value="appointment_letter">Appointment Letter</option>
                    <option value="salary_slip">Salary Slip</option>
                    <option value="id_proof">ID Proof</option>
                    <option value="address_proof">Address Proof</option>
                    <option value="education_certificate">Education Certificate</option>
                    <option value="experience_certificate">Experience Certificate</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Document Name *</label>
                <input type="text" name="document_name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File * (Max 10MB)</label>
                <input type="file" name="file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <input type="text" name="description"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                Upload Document
            </button>
        </div>
    </form>

    @if ($employee->documents->count() > 0)
        <div class="space-y-3">
            @foreach ($employee->documents as $document)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">{{ $document->document_name }}</h4>
                            <p class="text-xs text-gray-500">
                                {{ ucfirst(str_replace('_', ' ', $document->document_type)) }}
                                @if ($document->file_size)
                                    &middot; {{ number_format($document->file_size / 1024, 1) }} KB
                                @endif
                            </p>
                            @if ($document->description)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $document->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            View
                        </a>
                        <form action="{{ route('employees.documents.destroy', [$employee, $document]) }}" method="POST"
                              onsubmit="return confirm('Delete this document?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No documents uploaded yet.</p>
    @endif
</div>
