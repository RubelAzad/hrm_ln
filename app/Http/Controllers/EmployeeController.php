<?php

namespace App\Http\Controllers;

use App\Http\Requests\InitEmployeeExitRequest;
use App\Http\Requests\ReturnEmployeeAssetRequest;
use App\Http\Requests\StoreEmployeeAssetRequest;
use App\Http\Requests\StoreEmployeeContactRequest;
use App\Http\Requests\StoreEmployeeDocumentRequest;
use App\Http\Requests\StoreEmployeeHistoryRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\StoreEmployeeSkillRequest;
use App\Http\Requests\UpdateEmployeeExitRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\EmployeeAsset;
use App\Models\EmployeeContact;
use App\Models\EmployeeDocument;
use App\Models\EmployeeExit;
use App\Models\EmployeeHistory;
use App\Models\EmployeeSkill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Employee::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->with('supervisor')->latest()->paginate(15);

        $departments = Employee::distinct('department')->pluck('department')->filter();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create(): View
    {
        $supervisors = Employee::where('status', 'active')->get();

        return view('employees.create', compact('supervisors'));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['employee_id'] = $this->generateEmployeeId();
        $validated['status'] = 'active';
        $validated['user_id'] = $request->user()->id;

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee): View
    {
        $employee->load([
            'contacts',
            'histories',
            'skills',
            'documents',
            'assets',
            'exitRecord',
            'supervisor',
            'subordinates',
        ]);

        $departments = Employee::distinct('department')->pluck('department')->filter();

        return view('employees.show', compact('employee', 'departments'));
    }

    public function edit(Employee $employee): View
    {
        $supervisors = Employee::where('status', 'active')
            ->where('id', '!=', $employee->id)
            ->get();

        return view('employees.edit', compact('employee', 'supervisors'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    public function storeContact(StoreEmployeeContactRequest $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['is_primary'] ?? false) {
            $employee->contacts()->update(['is_primary' => false]);
        }

        $employee->contacts()->create($validated);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Emergency contact added successfully.');
    }

    public function destroyContact(Employee $employee, EmployeeContact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Contact deleted successfully.');
    }

    public function storeHistory(StoreEmployeeHistoryRequest $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['is_current'] ?? false) {
            $employee->histories()->update(['is_current' => false]);
        }

        $employee->histories()->create($validated);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employment history added successfully.');
    }

    public function destroyHistory(Employee $employee, EmployeeHistory $history): RedirectResponse
    {
        $history->delete();

        return redirect()->route('employees.show', $employee)
            ->with('success', 'History record deleted successfully.');
    }

    public function storeSkill(StoreEmployeeSkillRequest $request, Employee $employee): RedirectResponse
    {
        $employee->skills()->create($request->validated());

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Skill/Certification added successfully.');
    }

    public function destroySkill(Employee $employee, EmployeeSkill $skill): RedirectResponse
    {
        $skill->delete();

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Skill record deleted successfully.');
    }

    public function storeDocument(StoreEmployeeDocumentRequest $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validated();
        $file = $request->file('file');
        $path = $file->store('employee-documents/' . $employee->id, 'public');

        $employee->documents()->create([
            'document_type' => $validated['document_type'],
            'document_name' => $validated['document_name'],
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Document uploaded successfully.');
    }

    public function destroyDocument(Employee $employee, EmployeeDocument $document): RedirectResponse
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Document deleted successfully.');
    }

    public function storeAsset(StoreEmployeeAssetRequest $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validated();

        if (isset($validated['specification']) && is_string($validated['specification'])) {
            $validated['specification'] = json_decode($validated['specification'], true);
        }

        $employee->assets()->create($validated);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Asset assigned successfully.');
    }

    public function returnAsset(ReturnEmployeeAssetRequest $request, Employee $employee, EmployeeAsset $asset): RedirectResponse
    {
        $asset->update($request->validated());

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Asset return processed successfully.');
    }

    public function initExit(InitEmployeeExitRequest $request, Employee $employee): RedirectResponse
    {
        $employee->exitRecord()->create($request->validated());

        $employee->update(['status' => 'inactive']);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Exit process initiated successfully.');
    }

    public function updateExit(UpdateEmployeeExitRequest $request, Employee $employee, EmployeeExit $exit): RedirectResponse
    {
        $validated = $request->validated();
        $exit->update($validated);

        if ($validated['status'] === 'completed') {
            $employee->update(['status' => 'terminated']);
        }

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Exit record updated successfully.');
    }

    public function orgChart(): View
    {
        $employees = Employee::with('subordinates')
            ->whereNull('supervisor_id')
            ->where('status', 'active')
            ->get();

        return view('employees.org-chart', compact('employees'));
    }

    protected function generateEmployeeId(): string
    {
        $lastEmployee = Employee::withTrashed()
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastEmployee ? intval(substr($lastEmployee->employee_id, 4)) + 1 : 1;

        return 'EMP-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
