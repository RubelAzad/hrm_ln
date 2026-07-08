<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Assigned Assets</h3>
        <button onclick="document.getElementById('addAssetForm').classList.toggle('hidden')"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            + Assign Asset
        </button>
    </div>

    <form id="addAssetForm" method="POST" action="{{ route('employees.assets.store', $employee) }}" class="hidden mb-6 p-4 border border-gray-200 rounded-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Asset Type *</label>
                <select name="asset_type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="laptop">Laptop</option>
                    <option value="phone">Phone</option>
                    <option value="id_card">ID Card</option>
                    <option value="sim_card">SIM Card</option>
                    <option value="access_card">Access Card</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Asset Name *</label>
                <input type="text" name="asset_name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
                <input type="text" name="asset_serial"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                <input type="text" name="brand"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                <input type="text" name="model"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <input type="text" name="color"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Date *</label>
                <input type="date" name="assigned_date" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Condition *</label>
                <select name="condition_at_assignment" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                    <option value="new">New</option>
                    <option value="good">Good</option>
                    <option value="average">Average</option>
                    <option value="fair">Fair</option>
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Specifications (JSON)</label>
                <textarea name="specification" rows="2" placeholder='{"RAM": "16GB", "Storage": "512GB SSD"}'
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                Assign Asset
            </button>
        </div>
    </form>

    @if ($employee->assets->count() > 0)
        <div class="space-y-3">
            @foreach ($employee->assets as $asset)
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-medium text-gray-900">{{ $asset->asset_name }}</h4>
                                <span class="px-1.5 py-0.5 text-xs font-medium rounded
                                    @if ($asset->asset_type === 'laptop') bg-blue-100 text-blue-700
                                    @elseif ($asset->asset_type === 'phone') bg-green-100 text-green-700
                                    @elseif ($asset->asset_type === 'id_card') bg-yellow-100 text-yellow-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $asset->asset_type)) }}
                                </span>
                                <span class="px-1.5 py-0.5 text-xs font-medium rounded
                                    @if ($asset->status === 'assigned') bg-green-100 text-green-700
                                    @elseif ($asset->status === 'returned') bg-gray-100 text-gray-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($asset->status) }}
                                </span>
                            </div>
                            @if ($asset->asset_serial)
                                <p class="text-xs text-gray-500 mt-1">Serial: {{ $asset->asset_serial }}</p>
                            @endif
                            @if ($asset->brand || $asset->model)
                                <p class="text-xs text-gray-500">{{ $asset->brand }} {{ $asset->model }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">Assigned: {{ $asset->assigned_date->format('M d, Y') }}</p>
                            @if ($asset->return_date)
                                <p class="text-xs text-gray-500">Returned: {{ $asset->return_date->format('M d, Y') }}</p>
                            @endif
                            @if ($asset->specification)
                                <div class="mt-1 flex flex-wrap gap-1">
                                    @foreach ($asset->specification as $key => $value)
                                        <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">{{ $key }}: {{ $value }}</span>
                                    @endforeach
                                </div>
                            @endif
                            @if ($asset->notes)
                                <p class="text-xs text-gray-500 mt-1">{{ $asset->notes }}</p>
                            @endif
                        </div>
                        @if ($asset->status === 'assigned')
                            <button onclick="document.getElementById('returnAssetForm{{ $asset->id }}').classList.toggle('hidden')"
                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                Process Return
                            </button>
                        @endif
                    </div>

                    @if ($asset->status === 'assigned')
                        <form id="returnAssetForm{{ $asset->id }}" method="POST" action="{{ route('employees.assets.return', [$employee, $asset]) }}" class="hidden mt-4 p-3 border border-gray-200 rounded-lg">
                            @csrf
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Return Date *</label>
                                    <input type="date" name="return_date" required
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Condition *</label>
                                    <select name="condition_at_return" required
                                            class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                                        <option value="new">New</option>
                                        <option value="good">Good</option>
                                        <option value="average">Average</option>
                                        <option value="fair">Fair</option>
                                        <option value="damaged">Damaged</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Status *</label>
                                    <select name="status" required
                                            class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                                        <option value="returned">Returned</option>
                                        <option value="lost">Lost</option>
                                        <option value="damaged">Damaged</option>
                                    </select>
                                </div>
                                <div class="col-span-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                                    <input type="text" name="notes"
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                            <button type="submit" class="mt-3 px-3 py-1.5 bg-indigo-600 text-white rounded text-sm font-medium hover:bg-indigo-700 transition-colors">
                                Confirm Return
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No assets assigned yet.</p>
    @endif
</div>
