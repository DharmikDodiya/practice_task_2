<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function create(Request $request){
        return success('Create Access');
    }

    public function list(){
        return success('List Access');
    }

    public function update(Request $request,$id){
        return success('Update Access');
    }

    public function delete($id){
        return success('Delete Access');
    }
}
