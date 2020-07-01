<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\UserRole;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin= Role::create(['name'=>'Admin','description'=> 'Administrative Role']);

        $modules = ['user','role','permission','product'];

        foreach($modules as $module){
                
            $editPer = Permission::create(['name' => 'edit '.$module,'description'=>'Can Edit '.$module]);
            RolePermission::create(['role_id'=>$roleAdmin->role_id, 'permission_id'=>$editPer->permission_id]);

            $deletePer = Permission::create(['name' => 'delete '.$module,'description'=>'Can Delete '.$module]);
            RolePermission::create(['role_id'=>$roleAdmin->role_id, 'permission_id'=>$deletePer->permission_id]);

            $createPer = Permission::create(['name' => 'create '.$module,'description'=>'Can Create '.$module]);
            RolePermission::create(['role_id'=>$roleAdmin->role_id, 'permission_id'=>$createPer->permission_id]);

            $seePer = Permission::create(['name' => 'see '.$module,'description'=>'Can See '.$module]);
            RolePermission::create(['role_id'=>$roleAdmin->role_id, 'permission_id'=>$seePer->permission_id]);

        }

        UserRole::create(['user_id'=>1, 'role_id'=>$roleAdmin->role_id]);
    }
}
