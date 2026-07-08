<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Skills & Certifications</h3>
        <button onclick="document.getElementById('addSkillForm').classList.toggle('hidden')"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            + Add Skill
        </button>
    </div>

    <form id="addSkillForm" method="POST" action="{{ route('employees.skills.store', $employee) }}" class="hidden mb-6 p-4 border border-gray-200 rounded-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                <input type="text" name="skill_name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                <select name="category" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="skill">Skill</option>
                    <option value="certification">Certification</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Proficiency</label>
                <select name="proficiency"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                    <option value="expert">Expert</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Issued By</label>
                <input type="text" name="issued_by"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Issued Date</label>
                <input type="date" name="issued_date"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                <input type="date" name="expiry_date"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
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
                Save Skill
            </button>
        </div>
    </form>

    @if ($employee->skills->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($employee->skills as $skill)
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-medium text-gray-900">{{ $skill->skill_name }}</h4>
                                <span class="px-1.5 py-0.5 text-xs font-medium rounded
                                    @if ($skill->category === 'skill') bg-blue-100 text-blue-700
                                    @else bg-purple-100 text-purple-700 @endif">
                                    {{ ucfirst($skill->category) }}
                                </span>
                            </div>
                            @if ($skill->proficiency)
                                <div class="mt-2 flex items-center gap-1">
                                    @php $levels = ['beginner' => 1, 'intermediate' => 2, 'advanced' => 3, 'expert' => 4]; @endphp
                                    @for ($i = 1; $i <= 4; $i++)
                                        <div class="w-6 h-1.5 rounded-full {{ $i <= ($levels[$skill->proficiency] ?? 0) ? 'bg-indigo-500' : 'bg-gray-200' }}"></div>
                                    @endfor
                                    <span class="text-xs text-gray-500 ml-1 capitalize">{{ $skill->proficiency }}</span>
                                </div>
                            @endif
                            @if ($skill->issued_by)
                                <p class="text-xs text-gray-500 mt-1">Issued by: {{ $skill->issued_by }}</p>
                            @endif
                            @if ($skill->issued_date)
                                <p class="text-xs text-gray-500">Issued: {{ $skill->issued_date->format('M d, Y') }}</p>
                            @endif
                            @if ($skill->expiry_date)
                                <p class="text-xs text-gray-500">Expires: {{ $skill->expiry_date->format('M d, Y') }}</p>
                            @endif
                            @if ($skill->description)
                                <p class="text-sm text-gray-500 mt-1">{{ $skill->description }}</p>
                            @endif
                        </div>
                        <form action="{{ route('employees.skills.destroy', [$employee, $skill]) }}" method="POST"
                              onsubmit="return confirm('Delete this skill?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No skills or certifications added yet.</p>
    @endif
</div>
