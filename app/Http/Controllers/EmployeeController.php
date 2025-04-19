<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stancl\Tenancy\Facades\Tenancy;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index()
    {
        $employees = DB::table('employees')->get();
        return view('tenant.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('tenant.employees.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:Male,Female,Other',
            'birthdate' => 'nullable|date',
            'start_date' => 'nullable|date',
            'employment_status' => 'required|in:Active,On Leave,Resigned',
            'shift' => 'nullable|in:Morning,Afternoon,Night',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('employee_photos', $filename, 'public');
            $validated['photo'] = $path;
        }

        // Insert the employee into the database
        DB::table('employees')->insert($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified employee.
     */
    public function show($id)
    {
        $employee = DB::table('employees')->find($id);
        
        if (!$employee) {
            return redirect()->route('employees.index')
                ->with('error', 'Employee not found.');
        }
        
        return view('tenant.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit($id)
    {
        $employee = DB::table('employees')->find($id);
        
        if (!$employee) {
            return redirect()->route('employees.index')
                ->with('error', 'Employee not found.');
        }
        
        return view('tenant.employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, $id)
    {
        $employee = DB::table('employees')->find($id);
        
        if (!$employee) {
            return redirect()->route('employees.index')
                ->with('error', 'Employee not found.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:Male,Female,Other',
            'birthdate' => 'nullable|date',
            'start_date' => 'nullable|date',
            'employment_status' => 'required|in:Active,On Leave,Resigned',
            'shift' => 'nullable|in:Morning,Afternoon,Night',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            
            $photo = $request->file('photo');
            $filename = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('employee_photos', $filename, 'public');
            $validated['photo'] = $path;
        }

        // Update the employee in the database
        DB::table('employees')->where('id', $id)->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy($id)
    {
        $employee = DB::table('employees')->find($id);
        
        if (!$employee) {
            return redirect()->route('employees.index')
                ->with('error', 'Employee not found.');
        }
        
        // Delete photo if exists
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }
        
        // Delete the employee from the database
        DB::table('employees')->where('id', $id)->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
} 