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
        $roleAdmin= Role::create(['name'=>'Admin','guard_name'=> 'web']);

        $modules = ['user','role','permission','product'];

        foreach($modules as $module){
                
            $editPer = Permission::create(['name' => 'edit '.$module,'guard_name'=>'edit_'.$module]);
            RolePermission::create(['role_id'=>$roleAdmin->id, 'permission_id'=>$editPer->id]);

            $deletePer = Permission::create(['name' => 'delete '.$module,'guard_name'=>'delete_'.$module]);
            RolePermission::create(['role_id'=>$roleAdmin->id, 'permission_id'=>$deletePer->id]);

            $createPer = Permission::create(['name' => 'create '.$module,'guard_name'=>'create_'.$module]);
            RolePermission::create(['role_id'=>$roleAdmin->id, 'permission_id'=>$createPer->id]);

            $seePer = Permission::create(['name' => 'see '.$module,'guard_name'=>'see_'.$module]);
            RolePermission::create(['role_id'=>$roleAdmin->id, 'permission_id'=>$seePer->id]);

        }

        UserRole::create(['model_id'=>1,'model_type'=>'App\User','role_id'=>$roleAdmin->id]);
    }
}
