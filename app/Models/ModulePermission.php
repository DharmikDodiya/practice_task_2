<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'create',
        'view',
        'update',
        'delete',
        'module_id',
        'permission_id',
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
