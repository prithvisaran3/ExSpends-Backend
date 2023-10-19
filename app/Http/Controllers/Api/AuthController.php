<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){
        //validate
        $data = $request->validate([
            "name"=>"required",
            "email"=>"required|email|unique:user",
            "password"=>"required|min:6",
            "phone"=>"required|min:10|unique:user"
        ]);

        //create
        $usr = User::create([
            "name"=>$data["name"],
            "email"=>$data["email"],
            "password"=>Hash::make($data["password"]),
            "phone"=>$data["phone"]

        ]);
        
        
        //return response
        return response()->json([
            "status"=>200,
            "message"=>"Register Successfully",
            "token"=>$usr->createToken('secret')->plainTextToken,
            "data"=>$usr
        ],200);
    }
    public function login(Request $request){
        $data = $request->validate([
            "email"=>"required|email",
            "password"=> "required|min:6"
        ]);

        // if(!Auth::attempt($data)){
        //     return reposonse([
        //         "status"=>403,
        //         "message"=>"Invalid Credentials"
        //     ], 403);
        // }

        $usr = User::where("email", "=", $request->email)->first();

        if(isset($usr->id)){
            if(Hash::check($request->password, $usr->password)){
                return response()->json([
                    "status"=>200,
                    "message"=>"Login Succefully",
                    "token"=>$usr->createToken('secret')->plainTextToken,
                    "data"=>$usr
                ], 200);
            }else{
                return response()->json([
                    "status"=>404,
                    "message"=>"Password Didn't Match",
                ], 404);
            }
        }else{
            return response()->json([
                "status"=>404,
                "message"=>"Wrong Email or Customer Not Found",
            ], 404);    
        }
    }

    public function profile(){
        return response()->json([
            "status"=>200,
            "message"=>"Profile Get Successfully",
            "data"=>auth()->user()
        ]);
    }


    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            "status"=>200,
            "message"=>"Logout Successfully"
        ], 200);
    }


    public function update(Request $request,){
        $customer = auth()->user();
        $token = auth()->user()->id;
        $customer->name = $request->name ?? $customer->name;
        $customer->save();
       return response()->json([
        "status"=>200,
        "message"=>"Profile Update Successfully",
        "data"=>$customer
       ]);
    }
}
