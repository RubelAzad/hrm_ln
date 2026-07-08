@extends('layouts.admin')

@section('title', 'Talent Pools')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Talent Pools</h1>
        <button onclick="document.getElementById('createPoolForm').classList.toggle('hidden')"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">+ Create Pool</button>
    </div>

    <form id="createPoolForm" method="POST" action="{{ route('recruitment.talent-pools.store') }}" class="hidden mb-6 max-w-lg bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        @csrf
        <h2 class="text-lg font-semibold text-gray-900 mb-4">New Talent Pool</h2>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Criteria</label>
                <textarea name="criteria" rows="2" placeholder="Skills, experience level, etc."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ old('criteria') }}</textarea>
            </div>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">Create Pool</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($pools as $pool)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $pool->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $pool->candidates_count }} candidates</p>
                        @if ($pool->description)
                            <p class="text-sm text-gray-600 mt-2">{{ $pool->description }}</p>
                        @endif
                        @if ($pool->criteria)
                            <p class="text-xs text-gray-400 mt-1">Criteria: {{ $pool->criteria }}</p>
                        @endif
                    </div>
                    <span class="px-2 py-0.5 text-xs font-medium rounded
                        @if ($pool->status === 'active') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-700 @endif">{{ ucfirst($pool->status) }}</span>
                </div>
                <div class="flex gap-2 mt-4 pt-3 border-t border-gray-100">
                    <button onclick="document.getElementById('editPool{{ $pool->id }}').classList.toggle('hidden')"
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Edit</button>
                    <form action="{{ route('recruitment.talent-pools.destroy', $pool) }}" method="POST" class="inline"
                          onsubmit="return confirm('Delete this talent pool?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">Delete</button>
                    </form>
                </div>

                <form id="editPool{{ $pool->id }}" method="POST" action="{{ route('recruitment.talent-pools.update', $pool) }}" class="hidden mt-4 p-3 border border-gray-200 rounded-lg">
                    @csrf
                    <div class="space-y-2">
                        <input type="text" name="name" value="{{ $pool->name }}" required
                               class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        <textarea name="description" rows="2"
                                  class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">{{ $pool->description }}</textarea>
                        <input type="text" name="criteria" value="{{ $pool->criteria }}"
                               class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        <select name="status"
                                class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="active" {{ $pool->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $pool->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white rounded text-sm font-medium hover:bg-indigo-700">Update</button>
                    </div>
                </form>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-sm text-gray-500">No talent pools created yet.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $pools->links() }}</div>
@endsection
