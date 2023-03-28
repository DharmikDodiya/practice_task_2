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

    
    
}
