<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash; 

class UserController extends Controller
{
    public function index(){

        $users = User::where('id','>', 0)->get();
        
        if(!is_null($users))
        {
            return response()->json([
                'succes'=>true,
                'message'=>'Data Found',
                'data'=>$users
                ]);
        }
        else {
            return response()->json(['succes'=>false,'message'=>'Data Not Found',]);
        }
        

    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password'=>'required'
            ]);
            
            $newUser = new User();
    
            $newUser->name = $request->post('name');
            $newUser->email = $request->post('email');
            $newUser->password = Hash::make($request->post('password'));
    
            $newUser->save();
    
            return response()->json([
                'succes'=>true,
                'message'=>'User Created Success',
                'data'=>['id'=>$newUser->id]
                ]);

        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message'=>$th->getMessage(),'data'=>$th->response ],400);
        }
        
        
    }

    public function show($user)
    {
        $user = User::where('id', '=' , $user)->firstOrFail();
        
        return view('catalogs.users.edit')->with(['user'=>$user]);
    }

    public function update($id,UsersRequest $request)
    {
        $user = User::where('id', '=' , $id)->firstOrFail();

        $user->name = $request->post('name');
        $user->email = $request->post('email');
        $user->password = Hash::make($request->post('password'));

        $user->save();

        $user->assignRole($request->post('role'));

        return redirect()->route('users.index');

    }

    public function destroy($id)
    {
        user::destroy($id);

        return redirect()->route('users.index');
    }


    public function editPermissions($userId){

        $permissions = Permission::all();

        $user = User::findorFail($userId);

        $usPermission= $user->getAllPermissions();
        //dd($usPermission);
        return view('catalogs.users.permissions')->with([
            'permissions'=>$permissions,
            'user'=>$user,
            'usPermission'=>$usPermission
            ]);
    }

    public function savePermissions($userId, Request $request){
        $user = User::findorFail($userId);

        $permissions=[];
        if($request->has('permissions'))
        { 
            $permissions=array_keys($request->post('permissions'));
        }

        $user->syncPermissions($permissions);

        return redirect()->route('users.index');
    }
}
