<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\teacher\Teacher;
use App\Mail\TeacherEmailVerify;
use Illuminate\Support\Facades\Validator;
use Image;
use Storage;
use Hash;
use Tymon\JWTAuth\Facades\JWTAuth; //use this library


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:teacher', ['except' => ['login','register']]);
    }

    /**
     * Get Register
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        
        $validator = Validator::make($request->all(), 
        [
            'name' => 'required',
            'phone' => 'required|unique:teacher|min:10|max:10',
            'email' => 'required|unique:teacher|email:rfc',
            'password'=>'required',
            'address'=>'required',
            'about'=>'required',
            'profile_pic' => 'required|image|mimes:jpg,jpeg,png|max:1024',
        ],
        [
            'phone.unique'=>'This Phone No Already taken!',
            'email.unique'=>'This Email Already taken!',
            // 'profile_pic.max'=>'Profile picture size below 1M'
        ]);
        $errors = $validator->errors();
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Some error in input',
                'error' => $errors
            ], 400);
        }
        try {
            //code...
            $req_data=$request->all();
            $req_data['password']=bcrypt($request->password);
            $req_data['verification_code'] = (md5(uniqid().rand(111,999)));
            $user=Teacher::create($req_data);

            $uniqid=$user->uid;
            try {
                $image = $user->profile_pic;
                $input['imagename'] = md5(uniqid()).'.'.$image->extension();//getClientOriginalExtension();
                $destinationPath = public_path('storage/'.$uniqid);
                $img = Image::make($image->getRealPath());
                $img->resize(150, 150, function ($constraint) {
                //$constraint->aspectRatio();
                })->save($destinationPath.'/'.$input['imagename']);
    
                $user->profile_pic='storage/'.$uniqid.'/'.$input['imagename'];
                $user->save();
            } catch (\Exception $th) {
                $user->delete();
                return response()->json([
                    'error' => "Profile-Pic uploading error",
                ], 400);
            }
            \Mail::to($user->email)->send(new TeacherEmailVerify($user));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
            ], 400);
        }
        return response()->json([
            'message' => 'Teacher registered. Please check your email for verify.',
            'user' => $user,
        ], 200);
    }

    public function verify(Request $request)
    {
        $token=$request->token;
        $user = Teacher::where(['verification_code'=>$token,'email_verified_at'=>NULL])->count();
        if($user==0)
        return response()->json(['error' => 'Sorry! Token missmatch'], 400);
        Teacher::where('verification_code', $token)->update(
            ['verification_code' => '','email_verified_at' => now()]
        );
        return response()->json(['message' => 'Successfully email verified!'], 200);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {   
        $credentials = $request->only(['email','password']);
        if (! $token = auth()->guard('teacher')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 400);
        }
        else if ($token) {
            $user = Teacher::where([
                'email'=>$request->email,
                'email_verified_at'=>NULL,
                // 'password'=>bcrypt($request->password),
                ])->count();
            if($user==1)
            return response()->json(['error' => 'Sorry! Email ID is not verify!'], 400);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user=auth()->guard('teacher')->user();
        if(!$user){
            return response()->json(['error' => 'Unauthorized'], 400);
        }
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
        auth()->guard('teacher')->logout();
        return response()->json(['message' => 'Successfully logged out'],200);
        } catch (\Exception $th) {
            return response()->json(['error' => 'Invalid token'], 400);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        // $token = JWTAuth::getToken();
        // $apy = JWTAuth::getPayload($token)->toArray();
        try {
            // return $this->respondWithToken(auth()->guard('teacher')->refresh());
            return $this->createNewToken(auth()->guard('teacher')->refresh());
        } catch (\Exception $th) {
            return response()->json(['error' => 'Token missmatch!'],400);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('teacher')->factory()->getTTL() * 10
        ]);
    }
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('teacher')->factory()->getTTL() * 60,
            'user' => auth()->guard('teacher')->user()
        ]);
    }
}
