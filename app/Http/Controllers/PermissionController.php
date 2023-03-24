<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * create permissions
     */
    public function create(Request $request){
        $request->validate([
            'name'          => 'required|string|max:30|unique:permissions',
            'description'   => 'required|string|max:200'
        ]);

        $permission = Permission::create($request->only('name','description'));
        return success('permissions created successfully',$permission);
    }

    /**
     * list of all permissions
     */
    public function list(){
        $permission = Permission::all();
        if($permission){
            return success('permissions List',$permission);
        }
        return error(type:'notfound');
    }

    /**
     * update permissions
     */
    public function update(Request $request,Permission $id){
        $request->validate([
            'name'          => 'string|max:30|unique:permissions,name',
            'description'   => 'string|max:200'
        ]);
        if($id){
            $id->update($request->only('name','description'));
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
            return success('permissions deleted successfully');
        }
        return error(type:'notfound');
    }

    /**
     * get permission By permission id
     */
    public function get($id){
        $permission = Permission::find($id);
        if($permission){
            return success('get permission by permissions Id',$permission);
        }
        return error(type:'notfound');
    }


    // public function create(Request $request){
    //     $request->validate([
    //         'name'          => 'required|string|unique:permissions,name',
    //         'description'   => 'required|string|max:200',
    //         'module_id'            => 'exists:modules,id|required|array',
    //         'create'        => 'boolean',
    //         'view'          => 'boolean',
    //         'update'        => 'boolean',
    //         'delete'        => 'boolean',
    //     ]);

    //     $permission = Permission::create($request->only('name','description'));
    //     $moduleids = $request->module_id;
    //     $permission->modules()->attach($moduleids,['create' => $request->create,'view' => $request->view , 'update' => $request->update , 'delete' => $request->delete]);
    //     return success('your Permission Created Successfully',$permission);

    // }
}

