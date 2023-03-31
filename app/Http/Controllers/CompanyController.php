<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function create(Request $request){
        return success('create Access');
    }

    public function list(){
        return success('View Access');
    }

    public function update(Request $request,$id){
        return success('update Access');
    }

    public function delete($id){
        return success('Delete Access');
    }

    public function get($id){
        return success('Get Data By Id Access');
    }
}
