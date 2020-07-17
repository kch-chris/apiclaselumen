<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermissions extends Model
{
    protected $table="role_has_permissions";

    protected $fillable = [
        'permission_id' , 'role_id'
    ];

    public $timestamps=false;

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission','permission_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role','role_id');
    }
}
