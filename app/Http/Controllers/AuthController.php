<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    /**
     * SignUp Use Listing
     *
     * @return JSON $json
     * 
     */
    public function userList(){
        
        // List All SignUp User
        $users = User::toBase()->get();

        return response()->json([
            'user_list' =>  $users
        ],200);
    }

    /**
     * User Detail Id Wise
     *
     * @param mixed $id
     * 
     * @return JSON $json
     * 
     */
    public function userGet($id)
    {
        $user = User::where('id',$id)->first(); 
        return response()->json(['user' => $user],200); 
    }

    /**
     * Sign Up User Detail
     *
     * @return Message (error or success)
     * 
     */
    public function signupUser(Request $request){

        // Validation Check For SignUp User
        $validator = Validator::make($request->all(),[
            'user_name'         =>     'required|unique:users',
            'email'             =>     'required|unique:users|email|max:255',
            'first_name'        =>     'required',
            'last_name'         =>     'required',
            'password'          =>     'required|string|min:8|max:12',
            // 'remember_token'    =>     'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'error'     =>      $validator->messages()
            ],401);
        }

        // Create New User If Validation Success
        $user = new User();
        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->password = Hash::make($request->password);
        $user->user_type = $request->user_type;
        $user->remember_token = Str::random(25);
        $user->save();

        $userdata = array(
            'email'         =>  $request->email ,
            'password'      =>  $request->password
        );

        Auth::attempt($userdata);
        return response()->json([
            'success'   =>  $user["user_name"] . ' User Created',
            'user'      =>  $user,
            // 'token'     =>  $user->createToken("API TOKEN")->plainTextToken,
        ],200);
    }

    /**
     * SignIn User Detail
     *
     * @return Message (error or success)
     * 
     */
    public function signinUser(Request $request){

        $logincontent = $request->only('email','password');
        $validator = Validator::make($logincontent, [
            'email'     => 'required',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()
            ], 401);
        }

        $userdata = array(
            'email' => $request->email ,
            'password' => $request->password
        );
        
        if (Auth::attempt($userdata))
        {
            $user = User::where('email',$request->email)->first();
            return response()->json([
                'success' => 'Login sucecessfully',
                'api_token' =>   $user->createToken("api_token")->plainTextToken
            ], 200);
        }else{
              $error = 'Your Email Or Password is Wrong!!';
              return response()->json([
                'error' => $error
              ], 401);
        }
    }

    /**
     * Forgot Password Detail
     *
     * @return Message (error or success)
     * 
     */
    public function forgotPassword(Request $request){
        
        // Validation For forgot Password
        $validator = Validator::make($request->all(),[
            'email'     =>      'required',
            // 'password'  =>      'required|string|min:8|max:12'
        ]); 

        if($validator->fails()){
            return response()->json([
                'error' =>  $validator->messages()
            ],401);
        }

        // Check User Email Exist Or Not
        $email = $request->email;
        $check_user = User::where('email','=',$email)->exists();

        if($check_user == 1){

            $validator = Validator::make($request->all(),[
                'password'  =>  'required|min:8|max:12'
            ]);

            if($validator->fails()){
                return response()->json([
                    'error' =>  $validator->messages()
                ],401);
            }

            $update_password = [
                'password'  =>  Hash::make($request->password)
            ];

            // Update Password For Email User
            User::where('email',$email)->update($update_password);
            
            return response()->json([
                'success'   =>  'Password Updated Success'
            ],200);
        }else{
            return response()->json([
                'user_error'    =>  'Email User Error, Try Again!!!'
            ],401);
        }
    }

    /**
     * Remove User 
     *
     * @param mixed $id
     * 
     * @return Message (error or success)
     * 
     */
    public function removeUser($id)
    {
        $user = User::where('id',$id)->first();

        if($user){
            $user->delete();
            return response()->json(['success' => 'User Remove success'],200);
        }else{
            return response()->json(['error' => 'User Delete Error!!!'],401);
        }
    }
}
