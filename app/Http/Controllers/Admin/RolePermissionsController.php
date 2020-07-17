<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermissions;

class RolePermissionsController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->post('description'));
        try {
            $this->validate($request, [
                'permission_id' => 'required',
                'role_id'=>'required'
            ]);

            $newrolepermission = new RolePermissions();

            $newrolepermission->permission_id = $request->post('permission_id');
            $newrolepermission->role_id = $request->post('role_id');

            $newrolepermission->save();
    
            return response()->json([
                'succes'=>true,
                'message'=>'Successfully assigned permissions'
                ]);

        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message'=>$th->getMessage()],400);
        }
    }

    public function edit($id)
    {
        $role = Role::find($id);    
        

        $permissions = Permission::all();
        $rolePermission =Role::getPermissions($role->id);
        // dd($rolePermission);

        return view('catalogs.rolepermissions.edit')->with(['role'=>$role, 'rolePermissions'=>$rolePermission, 'permissions'=>$permissions]);
    }

    public function update($id,Request $request)
    {
        $role = Role::find($id);
        $permissions=[];
        if($request->has('permissions'))
        { 
            $permissions=array_keys($request->post('permissions'));
        }

        $role->syncPermissions($permissions);

        return redirect()->route('rolepermissions.index');

    }

    public function destroy($id)
    {
        RolePermissions::destroy($id);

        return redirect()->route('rolepermissions.index');
    }
}
