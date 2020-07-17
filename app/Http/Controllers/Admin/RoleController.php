<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RolePermissions;
use App\Models\Role;


class RoleController extends Controller
{
    /**
     * @OA\Put(
     *     path="/admin/role/assignPermission/{id}",
     *     tags={"Role"},
     *     summary="Assing Permission to Role",
     *     operationId="Update Permissions",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="Role id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                  @OA\Property(
     *                    type="array",
     *                    property="permissions",
     *                    description="Permissions Array",
     *                      @OA\Items(
     *                      type="integer"
     *                    )
     *                 )
     *             )
     *         )
     *     ),
     *      security={
     *         {"authorization": {}}
     *     }
     * )
     */
    public function assignPermission(Request $request, $id){
        
        try {

            $this->validate($request, [
                'permissions' => 'required'
            ]);

            $role = Role::where('id', '=' , $id)->first();

            if(!is_null($role))
            {
                $permissions=$request->post('permissions');
                RolePermissions::where('role_id',$role->id)->delete();
                
                foreach($permissions as $permission)
                {
                    $rolepermission= new RolePermissions();
                    $rolepermission->role_id=$role->id;
                    $rolepermission->permission_id=$permission;

                    $rolepermission->save();
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Role Updated Succesfully'
                ]);
                
            }
            else {
                return response()->json(['status'=>false, 'data' => [], 'message' => 'Role not found']);
            }
        } catch (\Throwable $th) {

            return response()->json(['status' => false,'message' => 'Error Ocurred','data' => $th->getMessage()],400);
        }

    }


    /**
     * @OA\Get(
     *     path="/admin/role/getPermission/{id}",
     *     tags={"Role"},
     *     summary="Show Role Permission",
     *     operationId="Show Permission",
     *     description="Returns permissions of role",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Parameter(
     *          name="id",
     *          description="Role id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     security={
     *         {"authorization": {}}
     *     }
     * )
     */
    public function getPermission($id)
    {
        $role= RolePermissions::where('role_id',$id)->with('permission')->get();

        if(!is_null($role))
        {
            return response()->json(['status'=>true, 'data' => $role]);
        }
        else{
            return response()->json(['status'=>false, 'data' => [], 'message' => 'Data not found']);
        }
    }
}
