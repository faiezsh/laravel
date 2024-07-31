<?php

namespace App\Http\Controllers;

use App\Models\DimensionsOfTheModel;
use Illuminate\Http\Request;

class DimensionsOfTheModelController extends Controller
{

    //
     /**
     * add
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    static public function add($id,$dx,$dy,$dz,$idM,$scale,$rotate)
    {
        try {
            //code...
            $Dimensions=DimensionsOfTheModel::create([
                'modelId'=>$id,
                'x'=>$dx,'y'=>$dy,'z'=>$dz,
                'roomModelId'=>$idM,
                'scale'=>$scale,
                'rotate'=>$rotate
            ]);
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }

    }
     /**
     * showDesign
     */
    public static function showDesign($id)
    {
        try {
            //code...
            $design=DimensionsOfTheModel::where('roomModelId',$id)->get([
                'modelId',
                'x',
                'y',
                'z',
                'scale',
                'rotate'
            ]);
            return $design;
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'state'=>false,
                'massege'=>$th->getMessage()
            ],500);
        }
    }

    public static function showModelswhitroom($id)
    {
        try {
            //code...
            $design=DimensionsOfTheModel::where('roomModelId',$id)->get([
                'modelId',
                'x',
                'y',
                'z',
                'rotate'
            ]);
            return $design;
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'state'=>false,
                'massege'=>$th->getMessage()
            ],500);
        }
    }
}
