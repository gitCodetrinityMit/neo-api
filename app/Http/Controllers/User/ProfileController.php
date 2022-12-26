<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    /**
     * Authantication User Detail List
     *
     * @return Json $json
     * 
     */
    public function userDetail() 
    {
        if(Auth::check()){
            $user = auth()->user()->id;
            $user_profile = User::where('id',$user)->get();
            return response()->json(['userProfile' => $user_profile],200);
        }
    }

    /**
     * Managed User Profile Detail In DataBase
     *
     * @param Request $request
     * 
     * @return Message (error or success)
     * 
     */
    public function updateUserProfile(Request $request)
    {   
       // Check Auth
       if(Auth::check()){

        // Validation For User Profile
        $validator = Validator::make($request->all(),[
            'email' =>  'required|email|unique:users,email',
            'user_name' => 'required',
            'phone_no'  =>  'required|numeric|digits:10'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->messages()],401);
        }

        $user_check = auth()->user()->id;
        $user = User::where('id',auth()->user()->id)->first();

        // Update User Profile
        $profile_name = $user->profile;
        if($request->file('profile')){
            // Validation Check For Update Profile
            $validator = Validator::make($request->all(),[
                'profile' =>  'required|image|mimes:jpg,png,jpeg'
            ]);

            if($validator->fails()){
                return response()->json(['error' => $validator->messages()],401);
            }

            // Delete Old Profile Image From Folder
            $path = public_path(). "/storage/$user->profile";
            $result = File::exists($path);
            if($result){
                File::delete($path);
            }
            
            // Select New Image
            $image = $request->file('profile');
            $profile_name = 'profile/'. rand(10000000,99999999). "." .$image->getClientOriginalExtension();
            $image->move(public_path('storage/profile/'),$profile_name);            
        }else{
            $profile_name = $user->profile;
        }

        // Update User Profile Detail
        $user_profile = [
            'user_name'   => $request->user_name,
            'email'       => $request->email,
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'password'    => Hash::make($request->password),
            'profile'     => $profile_name,
            'phone_no'    => $request->phone_no
        ];
        User::where('id',$user_check)->update($user_profile);
        return response()->json(['userProfile' => 'User Profile Updated'],200);
       }
    }
}
