<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * listing data using search and pagination
     */
    use ListingApiTrait;
    /**
     * create role
     */
    public function create(Request $request){
        $request->validate([
            'name'              => 'required|string|unique:roles,name|max:20',
            'description'       => 'string|max:200',
            'permissions_id'    => 'required|array|exists:permissions,id'
        ]);
        $permissionsids = $request->permissions_id;
        $role = Role::create($request->only('name','description'));
        $role->permissions()->attach($permissionsids);
        return success('role Created Successfully',$role);
    }

    /**
     * List Role
     */
    public function list(Request $request){

        $this->ListingValidation();
        $query = Role::query();
        $searchable_fields = ['name','description'];
        $data = $this->filterSearchPagination($query,$searchable_fields);

        return success('Role List',[
            'role' =>  $data['query']->get(),
            'count' =>  $data['count'],
        ]);
        
    }

    /**
     * update Role
     */
    public function update(Request $request,Role $id){
        $request->validate([
            'name'              => 'string|max:20|unique:roles,name',
            'description'       => 'string|max:200',
            'permissions_id'    => 'array|exists:permissions,id'
        ]);

        if($id){
            $permissionsids = $request->permissions_id;
            $id->update($request->only(['name','description']));
            $id->permissions()->syncWithoutDetaching($permissionsids);
            return success('role updated successfully',$id);
        }
        return error(type:'notfound');
    }

    /**
     * delete Role
    */
    public function delete($id){
        $role = Role::find($id);
        if($role){
            $role->permissions()->detach();
            $role->delete();
            return success('role deleted successfully');
        }
        return error(type:'notfound');
    }

    /**
     * get role By id
    */
    public function get($id){
        $role = Role::with('permissions','users')->find($id);
        if($role){
            return success('Role Details',$role);
        }
        return error(type:'notfound');
    }
}
