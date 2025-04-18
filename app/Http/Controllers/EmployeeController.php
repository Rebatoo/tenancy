<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->paginate(10);
        return view('tenant.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('tenant.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'gender' => 'nullable|in:Male,Female,Other',
            'birthdate' => 'nullable|date',
            'start_date' => 'required|date',
            'status' => 'required|in:Active,On Leave,Resigned',
            'shift' => 'required|in:Morning,Evening,Night,Custom',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('employee-photos', 'public');
            $validated['photo'] = $path;
        }

        Employee::create($validated);

        return redirect()->route('tenant.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        return view('tenant.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'gender' => 'nullable|in:Male,Female,Other',
            'birthdate' => 'nullable|date',
            'start_date' => 'required|date',
            'status' => 'required|in:Active,On Leave,Resigned',
            'shift' => 'required|in:Morning,Evening,Night,Custom',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $path = $request->file('photo')->store('employee-photos', 'public');
            $validated['photo'] = $path;
        }

        $employee->update($validated);

        return redirect()->route('tenant.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }
        
        $employee->delete();

        return redirect()->route('tenant.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
} 