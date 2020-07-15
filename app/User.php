<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Support\Facades\DB;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    public function checkPermission($permission){

        $havePer = DB::table('permissions')
                ->leftJoin('role_has_permissions', 'role_has_permissions.permission_id','=','permissions.id')
                ->leftJoin('model_has_roles','model_has_roles.role_id','=','role_has_permissions.role_id')
                ->where('model_has_roles.model_id','=',$this->id)
                ->where('model_has_roles.model_type','App\User')
                ->where('permissions.guard_name','=',$permission)
                ->first();

        $haveDirPer=DB::table('permissions')
                ->leftJoin('model_has_permissions', 'model_has_permissions.permission_id','=','permissions.id')
                ->where('model_has_permissions.model_id','=',$this->id)
                ->where('model_has_permissions.model_type','App\User')
                ->where('permissions.guard_name','=',$permission)
                ->first();

        if(!is_null($havePer) || !is_null($haveDirPer))
         return true;

         return false;

    }
}
