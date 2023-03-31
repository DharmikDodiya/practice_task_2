<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Traits\ListingApiTrait;

class ModuleController extends Controller
{
    /**
     * Listing Data using search and pagination 
     */
    use ListingApiTrait;
    /**
     * list Module
     */
    public function list(Request $request){
        $this->ListingValidation();
        $query = Module::query();

        $searchable_fields = ['name','description'];
        $data = $this->filterSearchPagination($query,$searchable_fields);

        return success('Module List',[
            'modules'   => $data['query']->get(),
            'count'     => $data['count']
        ]);
    }

    /**
     * create Module
     */
    public function create(Request $request){
        $request->validate([
            'name'          => 'required|string|min:3|max:30|unique:modules,name',
            'description'   => 'string'
        ],[
            'unique' => 'this :attribute already in modules table please  enter unique name values',
        ]);

        $module = Module::create($request->only('name','description'));
        return success('Module Created Successfully',$module);
    }

    /**
     * Update Module
     */

    public function update(Request $request,Module $id){
        $request->validate([
            'name'          => 'string|min:3|max:30|unique:modules,name',
            'description'   => 'string'
        ]);

        if($id){
            $id->update($request->only('name','description'));
            return success('Your Module Is Updated SuccessFully',$id);
        }
        return error('Your Module Is Not Updated',type:'notfound');
    }

    /**
     * get Module By id
    */

    public function get($id){
        $module = Module::with('permissions')->find($id);
        if($module){
        return success('Module Details',$module);
        }
        return error(type:'notfound');
    }

    /**
     * Delete Module
     */

    public function delete($id){
        $module = Module::find($id);
        if($module){
            $module->delete();
            return success('Module Deleted Successfully');
        }
        return error(type:'notfound');
    }

}