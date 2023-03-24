<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * create role
     */
    public function create(Request $request){
        $request->validate([
            'name'              => 'required|string|unique:roles,name|max:20',
            'description'       => 'string|max:200',
            //'permissions_id'    => 'array|exists:permissions,id'
        ]);

        $role = Role::create($request->only('name','description'));
        //$role->permissions()->attach($request->permissions_id);
        return success('role Created Successfully',$role);
    }

    /**
     * List Role
     */
    public function list(){
        $roles = Role::all();
        if($roles){
            return success('Roles lists',$roles);
        }
        return error(type:'notfound');
    }

    /**
     * update Role
     */
    public function update(Request $request,Role $id){
        $request->validate([
            'name'          => 'string|max:20|unique:roles,name',
            'description'   => 'string|max:200'
        ]);

        if($id){
            $id->update($request->only(['name','description']));
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
            $role->delete();
            return success('role deleted successfully');
        }
        return error(type:'notfound');
    }

    /**
     * get role By id
    */
    public function get($id){
        $role = Role::find($id);
        if($role){
            return success('Role Details',$role);
        }
        return error(type:'notfound');
    }
    

}
