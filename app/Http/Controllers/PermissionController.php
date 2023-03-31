<?php

namespace App\Http\Controllers;

use App\Models\ModulePermission;
use App\Models\Permission;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Listing Data using search and pagination
     */
    use ListingApiTrait;
    /**
     * create permissions
     */
    public function create(Request $request){
        $request->validate([
            'name'          => 'required|string|max:30|unique:permissions',
            'description'   => 'required|string|max:200',
            'create'        => 'boolean|nullable',
            'view'          => 'boolean|nullable',
            'update'        => 'boolean|nullable',
            'delete'        => 'boolean|nullable',
            'module_id'     => 'required|exists:modules,id'
        ]);
        
        $permission = Permission::create($request->only('name','description'));
        $modulepermission = $permission->modules()->attach($request->module_id,$request->only(['create','update','delete','view']));
        return success('permissions created successfully',$modulepermission);
    }

    /**
     * list of all permissions
     */
    public function list(Request $request){

        $this->ListingValidation();
        $query = Permission::query();
        $searchable_fields = ['name'];
        $data = $this->filterSearchPagination($query,$searchable_fields);

        return success('Permissions List',[
            'permissions'   =>  $data['query']->get(),
            'count'         =>  $data['count'],
        ]);
    }

    /**
     * update permissions
     */
    public function update(Request $request,Permission $id){
        $request->validate([
            'name'          => 'string|max:30',
            'description'   => 'string|max:200',
            'create'        => 'boolean|nullable',
            'view'          => 'boolean|nullable',
            'update'        => 'boolean|nullable',
            'delete'        => 'boolean|nullable',
            'module_id'     => 'exists:modules,id|array'
        ]);
        if($id){
            //dd($id);
            $id->update($request->only('name','description'));
            //dd($id);
            $id->modules()->updateExistingPivot($request->module_id,$request->only(['create' ,'update' ,'delete' ,'view']));
            
            return success('Permission Updated Successfuly',$id);
        }
        return error('permissions not updated',type:'forbidden');
    }

    /**
     * delete permissions 
     */
    public function delete($id){
        $permission = Permission::find($id);
        if($permission){
            $permission->delete();
            $permission->modules()->detach();
            return success('permissions deleted successfully');
        }
        return error(type:'notfound');
    }

    /**
     * get permission By permission id
     */
    public function get($id){
        $permission = Permission::with('modules')->find($id);
        if($permission){
            return success('get permission by permissions Id',$permission);
        }
        return error(type:'notfound');
    }
}

