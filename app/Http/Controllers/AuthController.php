<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){

        if(Cache::has("authData")){
            Cache::forget("authData");
        }

        $vCode = 1234;

        $authData = [
            'vCode' => $vCode,
            'mobile' => $request,
        ];

        Cache::put('authData',$authData , 120);

        $response = [
            'status'=> 'success',
        ];

        return response()->json($response);
    }

    public function verifyMobile(Request $request){
        if(Cache::has('authData')){
            $authData = Cache::get('authData');
            if($request->verifyCode == $authData['vCode']){
                $user = User::where('mobile',$authData['mobile'])->first();
                if($user){
                    $user->tokens()->where('name','AUTH_TOKEN')->delete();
                    $token = $user->createToken('AUTH_TOKEN')->plainTextToken;
                    $response = [
                        'status' => 200,
                        'token'=> $token,
                    ];
                }else{
                    $user = User::create([
                        'mobile'=> $authData['mobile'],
                    ]);
                    $token = $user->createToken('AUTH_TOKEN')->plainTextToken;
                    $response = [
                        'status' => 200,
                        'token'=> $token,
                    ];
                }
            }else{
                $response = [
                    'status'=> 403,
                    'message'=> 'invalid verify code',
                ];
            }
        }else{
            $response = [
                'status' => 402,
                'message' => 'something went wrong, please try again!'
            ];
        }
        return response()->json($response);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        $response = [
            'status'=> 200,
            'message'=> 'you are logged out',
        ];
        return response()->json($response);
    }
}
