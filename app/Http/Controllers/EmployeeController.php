<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Imports\CustomerImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function employees($type = null)
    {
        $title   = 'All Employees List';
        $employees = Employee::select('*')->orderBy('id', 'ASC')->get();
        $data = [
            'title'   => $title,
            'employees' => $employees,
        ];
        return view('admin.employee.employees', $data);
    }

    public function new_employee()
    {
        $title   = 'Create a Employee';
        $data = [
            'title'   => $title,
        ];
        return view('admin.employee.register', $data);
    }

    public function create_employee(Request $e)
    {
        $e->validate([
            'employee_name'  => 'required',
        ]);
        $employee_name = \App\Employee::where('employee_name', '=', $e->employee_name)->first();
        if ($employee_name === null) {
        $employee                  = new \App\Employees();
        $employee->employee_name   = $e->employee_name;
        $employee->save();
        run_queue();
             return redirect()->route('employees-list')->with('success-message','New Employee has been created');
        }else{
             return redirect()->route('employees-list')->with('error-message','Employee Already Exist Employee has not Created');
        }
    }

    public function assign_employee()
    {
        $title   = 'Assign Employees';
        $assignemployees = DB::table('assign_employee_to_company')->leftjoin('companies', 'assign_employee_to_company.company_id', '=', 'companies.id')->select('assign_employee_to_company.*','companies.*','employees.*')->leftjoin('employees', 'assign_employee_to_company.employee_id', '=', 'employees.id')->get()->toArray();
        $employees = Employee::select('*')->get();
        $companies = Company::select('*')->get();
        $data = [
            'title'   => $title,
            'employees' => $employees,
            'companies' => $companies,
            'assignemployees' => $assignemployees,
        ];
        return view('admin.employee.assign_employees', $data);
    }

    public function view_employee($id)
    {
        $employee = Employee::find($id);
        $data = [
             'title'    => $employee->employee_name,
             'employee'  => $employee,
         ];
         return view('admin.employee.employee_detail', $data);
    }

    public function edit_employee($id)
    {
        $result =  Employee::find($id);
        $data = [
            'title'   => $result->employee_name,
            'cdetail' => Employee::find($id),
        ];
        return view('admin.employee.edit_employee', $data);
    }

    public function edit_assignemployees($id)
    {
        $employees = Employee::select('*')->get();
        $companies = Company::select('*')->get();
        $data = [
            'title'   => 'Edit Assign Employee',
            'employees' => $employees,
            'companies' => $companies,
            'cdetail' => AssignEmployee::find($id),
        ];
        return view('admin.employee.edit_assignemployees', $data);
    }

    public function update_employee(Request $e)
    {
        $array = [
            'employee_name' => $e->employee_name,
        ];
        DB::table('employees')->where('id', $e->id)->update($array);
        return redirect()->back()->with('success-message','Employee updated successfully');
    }

    public function update_assign_employee_to_company(Request $e)
    {
        $array = [
            'employee_id'  =>   $e->employee_id, 
            'company_id'   =>   $e->company_id
        ];
        DB::table('assign_employee_to_company')->where('id', $e->id)->update($array);
         return redirect()->back()->with('success-message','Assigned Employee updated successfully');
    }

    public function delete_employee($id)
    {
        DB::table('employees')->where('id', $id)->delete();
        cache()->flush();
        return redirect()->route('employees-list')->with('warning-message','Employee has been deleted');
    }

    public function delete_assignemployees($id)
    {
        DB::table('assign_employee_to_company')->where('id', $id)->delete();
        return redirect()->route('assign-employee')->with('message','Assigned Employee has been deleted');
    }

    public function assign_employee_to_company(Request $e)
    {
        $e->validate([
            'employee_id'  => 'required',
            'company_id'  => 'required',
        ]);
        $product_name = \App\AssignEmployees::where('employee_id', '=', $e->employee_id)->where('company_id', '=', $e->company_id)->first();
        if ($product_name === null) {
        DB::table('assign_employee_to_company')->insert(
             array(
                    'employee_id'  =>   $e->employee_id, 
                    'company_id'   =>   $e->company_id
             )
        );
        cache()->flush();
            return redirect()->route('assign-employee')->with('success-message','Employee has been Assigend to a Company');
        }else{
            return redirect()->route('assign-employee')->with('error-message','Employee Already Assigend');
        }
    }
}
