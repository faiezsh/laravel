<?php

namespace App\Http\Controllers;

use App\Models\Model3D;
use App\Http\Controllers\Model3D_SupgategoryController;
use App\Models\Model3Dsupgategory;
use App\Models\Supgategory;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Model3DController extends Controller
{
    /**
     * addModel3D
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function addModel3D(Request $request)
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
            $Model3d = Validator::make($request->all(), [
                'modelName' => 'required|string',
                'path' => 'required|file|nullable',
                'name' => 'string'
            ]);
            if ($Model3d->fails()) {
                return response()->json([
                    'status' => false,
                    'massage' => 'validation error ',
                    'error' => $Model3d->errors()
                ], 401);
            }
            $SupCategory = Supgategory::where('supName', $request->name)->value('id');

            if ($SupCategory != null) {
                $path = $request->file('path');
                $filename = time() . $path->getClientOriginalName();
                Storage::disk('public')->put($filename, File::get($path));
                $Model3d1 = Model3D::create([
                    'modelName' => $request->modelName,
                    'path' => $filename,
                    'companyId' => Auth::id(),
                    'typeFile' => $request->path->getClientMimeType(),
                    'price' => $request->price,
                    'scale' => $request->scale,
                    'type'=>$request->type,
                    'x'=>$request->L,
                    'y'=>$request->H,
                    'z'=>$request->W
                ]);
                $t = Model3D_SupgategoryController::addModel($Model3d1['id'], $SupCategory);
                return response()->json([
                    'state' => true,
                    'massage' => 'sucssfully',
                ], 200);
            } else {
                return response()->json([
                    'state' => false,
                    'massage' => 'Error re-enter again ',
                ], 502);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * showModel3d
     */

    static public function get_user($id)
    {
        //engineer', 'company', 'supCombany
        $user = User::where('id', $id)->first(['companyId', 'roll']);
        if ($user->roll == "engineer") {
            $C = $user->companyId;
            return Model3DController::get_user($C);
        } else  if ($user->roll == "supCombany") {
            $C = $user->companyId;
            return Model3DController::get_user($C);
        } else if ($user->roll == "company") {
            return $id;
        }
    }

    public function get_models($supName)
    {
        try {
            //code...
            $userId = Model3DController::get_user(Auth::id());
            $id=Supgategory::where('supName',$supName)->value('id');
            $modelId = Model3Dsupgategory::where('supCategoryId', $id)->get('models3DId');
            $models = Model3D::whereIn('id', $modelId)->where('companyId', $userId)->get([
                'id',
                'modelName',
                'price'
            ]);
            return response()->json([
                'state' => true,
                'massage' => 'sucssfully',
                'models' => $models
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage()
            ], 500);
        }
    }


    public function models()
    {
        try {
            //code...
            $userId = Model3DController::get_user(Auth::id());
            $models = Model3D::where('companyId', $userId)->get([
                'id',
                'modelName',
                'price'
            ]);
            return response()->json([
                'state' => true,
                'massage' => 'sucssfully',
                'models' => $models
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage()
            ], 500);
        }
    }


    public function get_Modelby_ID($id)
    {
        $userId = Model3DController::get_user(Auth::id());
        $model=Model3D::where('id', $id)->where('companyId', $userId)->first([
            'scale',
            'modelName',
        ]);
        return response()->json([
            'state'=>true,
            'model'=>$model
        ],200);
    }

    public static function get($id)
    {
        $model=Model3D::where('id',$id)-> first([
            'id',
            'x',
            'y',
            'z',
            'type'
        ]);
        return $model;
    }

    public function showModel3D($id)
    {
        try {
            $userId = Model3DController::get_user(Auth::id());
            $modelCheck = Model3D::where('id', $id)->where('companyId', $userId)->first();
            if ($modelCheck != null) {
                $model = Model3D::where('id', $id)->first(['path', 'typeFile']);
                $file = Storage::disk('public')->get($model->path);
                return (new Response($file, 200))->header('Content-Type', $model->typeFile);
            }
            return response()->json([
                'status' => false,
                'massage' => 'Access is denied'
            ], 500);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage()
            ], 500);
        }
    }
}
