<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Details</h3>
    <dl class="grid grid-cols-2 gap-x-6 gap-y-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">First Name</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->first_name }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Last Name</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->last_name }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->date_of_birth?->format('M d, Y') ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Gender</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($employee->gender ?? '-') }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Marital Status</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($employee->marital_status ?? '-') }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Nationality</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->nationality ?? '-' }}</dd>
        </div>
    </dl>

    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-4">Contact Information</h3>
    <dl class="grid grid-cols-2 gap-x-6 gap-y-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">Email</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->email }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Phone</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->phone ?? '-' }}</dd>
        </div>
    </dl>

    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-4">Address</h3>
    <dl class="grid grid-cols-2 gap-x-6 gap-y-4">
        <div class="col-span-2">
            <dt class="text-sm font-medium text-gray-500">Address</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->address ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">City</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->city ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">State</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->state ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Postal Code</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->postal_code ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Country</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->country ?? '-' }}</dd>
        </div>
    </dl>

    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-4">Employment</h3>
    <dl class="grid grid-cols-2 gap-x-6 gap-y-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">Department</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->department ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Job Title</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->job_title ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Employment Type</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('-', ' ', $employee->employment_type ?? '-')) }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Supervisor</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->supervisor?->full_name ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Joining Date</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->joining_date?->format('M d, Y') ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Confirmation Date</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->confirmation_date?->format('M d, Y') ?? '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Direct Reports</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $employee->subordinates->count() }}</dd>
        </div>
    </dl>
</div>
