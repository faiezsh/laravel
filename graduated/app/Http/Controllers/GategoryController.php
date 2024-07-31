<?php

namespace App\Http\Controllers;

use App\Models\Gategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Summary of GategoryController
 */
class GategoryController extends Controller
{
    //
    /**
     * addGategory
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function addGategory(Request $request)
    {
        try {
            //code...
            $Gategory = Validator::make($request->all(), [
                'name' => 'required|string',
            ]);
            if ($Gategory->fails()) {
                return response()->json([
                    'status' => false,
                    'massage' => 'validation error ',
                    'error' => $Gategory->errors()
                ], 401);
            }
            $Gat = Gategory::create([
                'name' => $request->name
            ]);
            return response()->json([
                'state' => true,
                'massage' => 'sucssful',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Summary of showGategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function showGategory()
    {
        try {
            //code...
                   $Gategory = Gategory::all('id','name');
                    return response()->json([
                        'state' => true,
                        'massege' => 'sucssfully',
                        'Gategory' => $Gategory
                    ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage()
            ], 500);
        }
    }
}
