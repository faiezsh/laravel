<?php

namespace App\Http\Controllers;

use App\Models\Model3Dsupgategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Model3D_SupgategoryController extends Controller
{
    //
       /**
     * addModel
     * @param Request $request
     * @return
     */

    static public function addModel($model,$SupGategory)
     {
        try {
            $add=Model3Dsupgategory::create([
                'supCategoryId'=>$SupGategory,
                'models3DId'=>$model
            ]);
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }

     }
}
