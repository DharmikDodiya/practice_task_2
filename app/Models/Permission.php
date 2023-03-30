<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function modulePermissions()
    {
        return $this->belongsToMany(Module::class,'module_permissions','module_id','permission_id');
    }

    /**
     * many To many relation on role and permissions
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_permissions','permissions_id','roles_id');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class,'module_permissions','permission_id','module_id')->withPivot(['create','view','update','delete']);
    }

    public $module_code,$access;
    public function hasAccess($module_code,$access){
        foreach($this->modules as $module){       
            //dd($module->pivot->$access);
            $check = $module->where('name',$module_code)->first();
            //dd($check);
            if($check && $module->pivot->$access == 1){
                //dd('ok');
                return true;
            }
            else{
                //dd('not access');
                return false;
            }
        }
    }
}