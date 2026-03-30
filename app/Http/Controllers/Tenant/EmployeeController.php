<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('name')->get();
        return view('tenant.hr.employee.index', compact('employees'));
    }

    public function create()
    {
        return view('tenant.hr.employee.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees,employee_id',
            'name'        => 'required|string|max:255',
            'position'    => 'nullable|string',
            'basic_salary'=> 'required|numeric|min:0',
            'allowance'   => 'nullable|numeric|min:0',
        ]);

        Employee::create($request->all());

        return redirect()->route('tenant.hr.employee.index', tenant('id'))
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(Employee $employee)
    {
        return view('tenant.hr.employee.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees,employee_id,' . $employee->id,
            'name'        => 'required|string|max:255',
            'basic_salary'=> 'required|numeric|min:0',
        ]);

        $employee->update($request->all());

        return redirect()->route('tenant.hr.employee.index', tenant('id'))
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('tenant.hr.employee.index', tenant('id'))
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}
