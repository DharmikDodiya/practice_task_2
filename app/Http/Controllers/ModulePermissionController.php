<?php

namespace App\Http\Controllers;

use App\Models\ModulePermission;
use Illuminate\Http\Request;
use App\Traits\ListingApiTrait;

class ModulePermissionController extends Controller
{
    /**
     * listing data using search and pagination
     */
    use ListingApiTrait;

    /**
     * create ModulePermission
     */
    public function create(Request $request)
    {
        $request->validate([
            'create'        => 'boolean|numeric|max:1',
            'view'          => 'boolean|numeric|max:1',
            'update'        => 'boolean|numeric|max:1',
            'delete'        => 'boolean|numeric|max:1',
            'module_id'     => 'required|numeric|exists:modules,id|unique:module_permissions,module_id',
            'permission_id' => 'required|numeric|exists:permissions,id'
        ]);
        $module_permission = ModulePermission::create($request->only(
     'create',
            'view',
            'update',
            'delete',
            'module_id',
            'permission_id'
        ));

        return success('Module Permission Created Successfuly', $module_permission);
    }

    /**
     * list ModulePermission
     */
    public function list(Request $request)
    {
        $this->ListingValidation();
        
        $query = ModulePermission::query();
        $searchable_fields = ['module_id']; 
        $data = $this->filterSearchPagination($query,$searchable_fields);

        return success('ModulePermission List',[
            'users' =>  $data['query']->get(),
            'count' =>  $data['count'],
        ]);
    }

    /**
     * update ModulePermission
     */
    public function update(Request $request ,ModulePermission $id){
        $request->validate([
            'create'        => 'boolean|numeric|max:1',
            'view'          => 'boolean|numeric|max:1',
            'update'        => 'boolean|numeric|max:1',
            'delete'        => 'boolean|numeric|max:1',
        ]);

        if($id){
            $id->update($request->only([
                'create',
                'update',
                'view',
                'delete'
            ]));
            return success('modulePermission Updated Successfully',$id);
        }
        return error('ModulePermission Not Updated');
    }

    /**
     * Delete ModulePermission
     */
    public function delete($id){
        $module_permission = ModulePermission::find($id);
        if($module_permission){
            $module_permission->delete();
            return success('ModulePermission Deleted Successfully');
        }
        return error(type:'notfound');
    }

    /**
     * get modulePermission By id
     */
    public function get($id){
        $module_permission = ModulePermission::with('module','permission')->get();
        if($module_permission){
            return success('get Module , permission and modulePermission by MudulePermission Id',$module_permission);
        }
        return error(type:'notfound');
    }


}
