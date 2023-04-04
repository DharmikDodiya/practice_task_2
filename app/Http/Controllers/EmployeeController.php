<?php

namespace App\Http\Controllers;

use PgSql\Lob;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\ListingApiTrait;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    use ListingApiTrait;
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
        $this->ListingValidation();
            $query = Employee::query();
    
            $searchable_fields = ['name','designation','email'];
            $data = $this->filterSearchPagination($query,$searchable_fields);
            Log::channel('dharmik')->emergency("Employee List Form Custom Log File");
            return success('Employee List',[
                'Employee'       => $data['query']->get(),
                'count'          => $data['count']
            ]);
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
