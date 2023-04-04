<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'name'          => 'required|string|max:30',
            'email'         => 'required|unique:employees,email',
            'designation'   => 'required|string',
            'salary'        => 'required|numeric'
        ]);
        $employee = Employee::create($request->only('name','email','designation','salary'));
        return success('Employee Created Successfully',$employee);
    }

    public function list(){
        $employee = Employee::all();
        if($employee){
            return success('Employee List',$employee);
        }
        return error('Data Not Found',type:'notfound');
    }

    public function update(Request $request,Employee $id){
        $request->validate([
            'name'          => 'string|max:30',
            'email'         => 'email',
            'designation'   => 'string',
            'salary'        => 'numeric'
        ]);
        if($id){
            $id->update($request->only('name','email','designation','salary'));
            return success('Your Employee Is Updated SuccessFully',$id);
        }
        return error('Your Employee Is Not Updated',type:'notfound');
    }

    public function delete($id){
        $employee = Employee::find($id);
        if($employee){
            $employee->delete();
            return success('Employee Deleted Successfully');
        }
        return error('Employee Not Deleted');
    }

    public function get($id){
        $employee = Employee::findOrFail($id);
        return success('Get Employee Data By ID',$employee);
    }
}
