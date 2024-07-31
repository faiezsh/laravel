<?php

namespace App\Http\Controllers;

use App\Models\Gategory;
use App\Models\Supgategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Summary of SupgategoryController
 */
class SupgategoryController extends Controller
{
    //
      /**
     * addGategory
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

     public function addSupGategory(Request $request)
     {
        try {
            //code...
            $roll=User::where('id',Auth::id())->value('roll');
            if ($roll!="company")
            {
                return response()->json([
                    'state'=>false,
                    'massege'=>"Access is denied"
                ]);
            }
            $SupGategory=Validator::make($request->all(),[
              'supName'=>'required|string',
              'name'=>'required|string'
                 ]);
            if ($SupGategory->fails()) {
                return response()->json(['status' => false,
                'massage' => 'validation error ',
                'error' => $SupGategory->errors()],401);
            }
            $name=Supgategory::where('supName',$request->supName)->value('id');
            if ($name!=null)
            {
                return response()->json([
                    'status' => false,
                    'massage' => 'the name category already exist'
                ], 422);
            }
            $Id=Gategory::where('name',$request->name)->value('id');
            $Cat=Supgategory::create([
                'supName'=>$request->supName,
                'user_id'=>Auth::id(),
                'categoryId'=>$Id
            ]);
            return response()->json([
                'state'=>true,
                'massage' => 'sucssful'
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
      * Summary of show_supCategory
      * @param mixed $id
      * @return \Illuminate\Http\JsonResponse
      */
     public function show_supCategory($name)
     {
       try {
        //code...

        $cat=Gategory::where('name',$name)->first('id');
        $id=Model3DController::get_user(Auth::id());
        $supCategory=Supgategory::where('categoryId',$cat->id)->where('user_id',$id)->get(['supName','id']);

        if ($supCategory[0]->supName!=null)
        {
            return response()->json([
                'states'=>true,
                'massege'=>'sucssefully',
                'supCategory'=>$supCategory
            ],200);
        }
        return response()->json([
            'state'=>false,
            'massege'=>'the category is not found'
        ],200);
       } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
            'status' => false,
            'massage' => $th->getMessage()
        ], 500);
       }
     }
     public function show_sup()
     {
       $supCategory=Supgategory::where('user_id',Auth::id())->get();
       return response()->json([
        'supCategory'=>$supCategory
       ],200);

     }
}
