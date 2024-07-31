<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


/**
 * Summary of AuthController
 */
class AuthController extends Controller
{
    /**
     * create user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function creatcompany(Request $request)
    {
        try {
            $company = Validator::make($request->all(), [
                //'roll' => 'string',
                //'companyId' => 'integer|nullable',
                'address' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string:min 8',
                'name' => 'string',
                'userName' => 'required|string'
            ]);
            if ($company->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'massage' => 'validation error ',
                        'error' => $company->errors()
                    ],
                    401
                );
            }
            $user1 = User::where('userName', $request->userName)->value('id');
            if ($user1 != null) {
                return response()->json([
                    'status' => false,
                    'massage' => 'the userName exist'
                ],422);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'userName' => $request->userName,
                'roll' => "Company"
            ]);

            return response()->json([
                'status' => true,
                'massage' => 'sucssful',
                'token' => $user->createToken("Api")->plainTextToken,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'address' => $user->address,
                    'userName' => $user->userName,
                    'roll' => $user->roll
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage()
            ], 500);
        }
    }
    ////////////////////////
    /**
     * Summary of creatUser
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        try {
            //code...

            $company = Validator::make($request->all(), [
                'address' => 'required|string',
                'roll' => 'string',
                'companyId' => 'integer',
                'email' => 'required|email',
                //'verifyEmail' => 'required|string',
                'password' => 'required|string:min 8',
                'name' => 'required|string',
                'userName' => 'required|string'
            ]);

            if ($company->fails()) {
                return response()->json(['status' => false, 'massage' => 'validation error ', 'error' => $company->errors()], 401);
            }

            $user1 = User::where('userName', $request->userName)->value('id');
            if ($user1!=null) {
                return response()->json([
                    'status' => false,
                    'massage' => 'The username already exists'
                ], 422);
            }

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'userName' => $request->userName,
                'name' => $request->name,
                'roll' => $request->roll,
                'companyId' => Auth::id()
            ]);
            return response()->json([
                'status' => true,
                'massage' => 'sucssful',
                'userName' => $user->userName
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage()
            ], 500);
        }
    }
    //////////////////////////////////////////
    /**
     * login
     * @param Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $validate = Validator::make(
            $request->all(),
            [
                'userName' => 'required|string',
                'password' => 'required|string'
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'massage' => 'validation error ',
                'error' => $validate->errors()
            ], 401);
        }
        if (!Auth::attempt($request->only((['userName', 'password'])))) {
            return response()->json([
                'state' => false,
                'massage' => 'username & password dose not match with our record',
            ],399);
        }
        $user = User::where('userName', $request->userName)->first();
        return response()->json([
            'stat' => true,
            'massage' => 'sucssful',
            'token' => $user->createToken("Api")->plainTextToken,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'userName' => $user->userName,
                'roll' => $user->roll
            ]
        ],200);
    }
    //////////////////////////////////////
    /**
     * show user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function showUser()
    {
        try {
            //code...
            $user = User::where('companyId', Auth::id())->where('roll', "engineer")->get(['userName', 'email', 'address', 'name']);
            return response()->json([
                'stat' => true,
                'massage' => 'sucssful',
                'user' => $user
            ],200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * showCompany
     */
    public function showCompany()
    {
        try {
            //code...
            $user = User::where('companyId', Auth::id())->where('roll', "supCombany")->get(['userName', 'email', 'address', 'name']);
            return response()->json([
                'stat' => true,
                'massage' => 'sucssful',
                'user' => $user
            ],200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage()
            ], 500);
        }
    }
    //////////////////
    /**
     * showUser_subCompany
     */
    public function showUser_subCompany($id)
    {
        try {
            //code...
            $user = User::where('companyId', $id)->where('roll', "engineer")->get(['userName', 'email', 'address', 'name']);
            return response()->json([
                'stat' => true,
                'massage' => 'sucssful',
                'user' => $user
            ],200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage()
            ], 500);
        }
    }
    ///////////////
    /**
     * logout
     */
     public function logout()
     {
       try {
        //code...
       auth()->user()->tokens()->delete();
        return response()->json([
            'stat'=>true,
            'massege'=>'logged out successfully'
        ],200);
    } catch (\Throwable $th) {
     //throw $th;
     return $th->getMessage();
    }
     }
    ///////////////////////////////////
    /**
     * __construct
     */
    public function __construct()
    {
    }
}
