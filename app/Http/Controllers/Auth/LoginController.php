<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 

     /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Auth"},
     *     summary="Login de usuario",
     *     operationId="Login",
     *      @OA\Response(
     *         response=200,
     *         description="Success Request"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true, 
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",     
     *                 @OA\Property(
     *                     property="email",
     *                     description="usuario del sistema",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="contraseña de usuario",
     *                     type="string"
     *                 ),
     * *                  required={"email","password"},
     *             )
     *         )
     *     )
     * )
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);
        $user = User::where('email', $request->input('email'))->first();
        
        if(!$user){
            return response()->json(['status'=>false, 'message'=>'Email does not exist'],400);
        }

        if(Hash::check($request->input('password'), $user->password)){
            
            $refresh_token=Str::random(100);
            
            $user->refresh_token=$refresh_token;
            $user->save();
            
            return response()->json(['status' => true,'data'=>[
                'token' => $this->jwt($user),
                'refresh_token'=>$refresh_token
            ],'message'=>'Login successfully']);
        }else{
            return response()->json(['status' => false,'message'=>'An Error is occurred'],400);
        }
    }   
}
