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

    /**
     * @OA\Get(
     *     path="/admin/user",
     *     tags={"User"},
     *     summary="Listado de Usuarios",
     *     operationId="listuser",
     *      @OA\Response(
     *         response=200,
     *         description="Success Request"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized request"
     *      ),
     *     security={
     *         {"authorization": {}}
     *     }
     *     
     * )
     */
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
            return response()->json(['succes'=>false,'message'=>'Data Not Found'],400);
        }
        

    }


    /**
     * @OA\Post(
     *     path="/admin/user",
     *     tags={"User"},
     *     summary="Crear Usuario",
     *     operationId="createuser",
     *      @OA\Response(
     *         response=200,
     *         description="Success Request"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized request"
     *      ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true, 
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",     
     *                 @OA\Property(
     *                     property="name",
     *                     description="Nombre del Usuario",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="Email del Usuario",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Contraseña del Usuario",
     *                     type="string",
     *                 ),
     * *               required={"name","email","password"},
     *             )
     *         )
     *     ),
     *     security={
     *         {"authorization": {}}
     *     }
     *     
     * )
     */
    public function store(Request $request)
    {
        // dd($request->post("name"));
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


    /**
     * @OA\Put(
     *     path="/admin/user/{id}",
     *     tags={"User"},
     *     summary="Actualiza Usuario",
     *     operationId="updateuser",
     *      @OA\Response(
     *         response=200,
     *         description="Success Request"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized request"
     *      ),
     *     @OA\Parameter(
     *          name="id",
     *          description="Id de Usuario",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true, 
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",     
     *                 @OA\Property(
     *                     property="name",
     *                     description="Nombre del Usuario",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="Email del Usuario",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Contraseña del Usuario",
     *                     type="string",
     *                 ),
     * *               required={"name","email"},
     *             )
     *         )
     *     ),
     *     security={
     *         {"authorization": {}}
     *     }
     *     
     * )
     */
    public function update($id,Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$id.',id'
            ]);

            $user = User::where('id', '=' , $id)->firstOrFail();

            $user->name = $request->post('name');
            $user->email = $request->post('email');

            if(!is_null($request->post('password')))
                $user->password = Hash::make($request->post('password'));

            $user->save();
    
            return response()->json([
                'succes'=>true,
                'message'=>'User Updated Successfully',
                'data'=>['id'=>$user->id]
                ]);

        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message'=>$th->getMessage() ],400);
        }

    }
    

    /**
     * @OA\Delete(
     *     path="/admin/user/{id}",
     *     tags={"User"},
     *     summary="Elimina Usuario",
     *     operationId="deleteuser",
     *      @OA\Response(
     *         response=200,
     *         description="Success Request"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized request"
     *      ),
     *     @OA\Parameter(
     *          name="id",
     *          description="Id de Usuario",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     security={
     *         {"authorization": {}}
     *     }
     *     
     * )
     */
    public function destroy($id)
    {
        try {
            User::destroy($id);
            
            return response()->json([
                'succes'=>true,
                'message'=>'User Deleted Successfully'
                ]);

        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message'=>$th->getMessage(),'data'=>$th->response ],400);
        }
    }

}
