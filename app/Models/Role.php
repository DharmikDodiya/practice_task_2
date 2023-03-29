<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    protected $hidden =[
        'created_at',
        'updated_at'
    ];

     /**
     * many To many relation on role and permissions
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'role_permissions','roles_id','permissions_id');
    }

    /**
     * many to many relatonship on role to users
     */
    public function users()
    {
        return $this->belongsToMany(User::class,'user_roles','roles_id','users_id');
    }
    
    public $module_code,$access;
    
    public function hasAccess($module_code,$access){
        foreach($this->permissions as $permission){
            if($permission->hasAccess($module_code,$access)){
                return true;
            }
        }
    }



}
