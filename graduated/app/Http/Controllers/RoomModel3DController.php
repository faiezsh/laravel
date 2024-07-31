<?php

namespace App\Http\Controllers;

use App\Models\RoomModel3D;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DimensionsOfTheModelController;
use App\Models\Model3D;
use App\Http\Controllers\DoorController;
use App\Http\Controllers\WindoController;
use App\Models\Windo;
use App\Models\door;

class RoomModel3DController extends Controller
{
    /**
     * get id company
     */
    public function Id($id)
    {
        $user = User::where('id', $id)->first();
        return $user->companyId;
    }

    /**
     * get id room
     */
    public function roomId($id)
    {
        if (RoomModel3D::where('id', $id)->value('id') != null) {
            RoomModel3DController::roomId($id + 1);
        }
        return ($id);
    }
     /**
      * get_price
      */
      public function get_price($request)
      {
        $sum=0;
        for ($i = 0; $i < count($request); $i++) {
              $P=Model3D::where('id',$request[$i][0])->value('price');
              $sum=$sum +($P);
        }
        return ($sum);
      }
    /**
     * Room design
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function roomDesign(Request $request)
    {
        try {
            //code...
            $roll=User::where('id',Auth::id())->value('roll');
            if ($roll=="company"||$roll=="supCombany")
            {
                return response()->json([
                    'state'=>false,
                    'massege'=>"Access is denied"
                ]);
            }
            $model=array();
            $models = $request->model;
            $i=0;
            foreach ($models as $m)
            {
                $model[$i][0]=$m['id'];
                $model[$i][1]=$m['x'];
                $model[$i][2]=$m['y'];
                $model[$i][3]=$m['z'];
                $model[$i][4]=$m['scale'];
                $model[$i][5]=$m['rotate'];
                $i++;
            }
            $price=RoomModel3DController::get_price($model);
            $companyId = RoomModel3DController::Id(Auth::id());
            $roomModel = RoomModel3D::create([
                'companyId' => $companyId,
                'userId' => Auth::id(),
                'roomModelName' => $request->roomModelName,
                'price' => $price ,
                'highe' =>$request->highe,
                'whidth'=>$request->whidth,
                'Length'=>$request->length,
                'rate'=>$request->rate,
                'color'=>$request->color
            ]);
            foreach ($request->door as $door)
            {
                $t=DoorController::addDoor($roomModel['id'],$door['pos'],$door['width'],$door['location']);
            }
            foreach ($request->window as $windo)
            {
                $t=WindoController::addWindo($roomModel['id'],$windo['pos'],$windo['width'],$windo['location']);
            }
            $t=true;
            for ($i = 0; $i < count($model); $i++) {
                if ($t)
                {
                    $h = DimensionsOfTheModelController::add($model[$i][0], $model[$i][1], $model[$i][2], $model[$i][3], $roomModel['id'],$model[$i][4],$model[$i][5]);
                }
                if (!$h)
                {
                 $t=false;
                }
            }
            if (!$t)
            {
             $resalt=RoomModel3D::where('id',$roomModel['id'])->delete();
             $window=Windo::where('roomId',$roomModel['id'])->delete();
             $door=door::where('roomId',$roomModel['id'])->delete();
             return response()->json([
                'state' => false,
                'massege' =>'Error re-enter again'
             ],302);
            }
        $models=DimensionsOfTheModelController::showModelswhitroom($roomModel['id']);
        $room['rate']=$roomModel['rate'];
        $room['length']=$roomModel['length'];
        $room['width']=$roomModel['whidth'];
        $room['highe']=$roomModel['highe'];
        $room['models']=$models;
        $rate=recommendationSystem::Room($room);
            return response()->json([
                'stat' => true,
                'massege' => 'seccssfully',
                'price' => $price
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'state' => false,
                'massege' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * show  Design Display Engineer
     */

    public function showDesignDisplayEngineer($user)
    {
        $engineer = array();
        for ($i = 0; $i < count($user); $i++) {

            $engineer[$i] = RoomModel3D::where('userId', $user[$i]->id)->get(['roomModelName', 'id']);
        }
        return ($engineer);
    }

    /**
     * showDesignDisplaysupCompany
     */

    public function showDesignDisplaysupCompany($user)
    {
        $supCompany = array();
        for ($i = 0; $i < count($user); $i++) {
            $supCompany[$i] = RoomModel3D::where('companyId', $user[$i]->id)->get(['roomModelName', 'id']);
        }
        return ($supCompany);
    }
    /**
     * Design Display
     */
    public function designDisplay($roll)
    {
        try {
            //code...
            if ($roll == "engineer") {
                $user = User::where('companyId', Auth::id())->where('roll', $roll)->get('id');
                return response()->json([
                    'stat' => true,
                    'massege' => 'succssfully',
                    'Work' => RoomModel3DController::showDesignDisplayEngineer($user)
                ]);
            }
            if ($roll == "supCombany") {
                $user = User::where('companyId', Auth::id())->where('roll', $roll)->get('id');
                $user = json_decode($user);
                return response()->json([
                    'stat' => true,
                    'massege' => 'succssfully',
                    'Work' => RoomModel3DController::showDesignDisplaysupCompany($user)
                ], 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'state' => false,
                'massege' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * showdesignDisplay
     */
    public function showdesignDisplay($userName)
    {
        try {
            //code...
            $user = User::where('userName', $userName)->first(['id', 'companyId']);
            $design = RoomModel3D::where('userId', $user->id)->where('companyId', $user->companyId)->get(['roomModelName', 'id','price']);
            return response()->json([
                'stat' => true,
                'massege' => 'succssfully',
                'room' => $design
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'state' => false,
                'massege' => $th->getMessage()
            ], 500);
        }
    }

    public function showdesignDisplaybyUser()
    {
        try {
            //code...
            $user = User::where('id', Auth::id())->first(['companyId']);
            $design = RoomModel3D::where('userId',Auth::id())->where('companyId', $user->companyId)->get(['roomModelName', 'id','price']);
            return response()->json([
                'stat' => true,
                'massege' => 'succssfully',
                'room' => $design
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'state' => false,
                'massege' => $th->getMessage()
            ], 500);
        }
    }

    public function get_room($id)
    {
        try {
            //code...
            $companyId = RoomModel3DController::Id(Auth::id());
            $room=RoomModel3D::where('id',$id)->where('userId',Auth::id())->where('companyId',$companyId)->get([
                'highe',
                'whidth',
                'Length',
                'color'
            ]);

            $models=DimensionsOfTheModelController::showDesign($id);

            $door=DoorController::getDoor($id);
            $windo=WindoController::getWindo($id);
            $roomN=array();
            $roomN["color"]=$room[0]->color;
            $roomN["highe"]=$room[0]->highe;
            $roomN["width"]=$room[0]->whidth;
            $roomN["Length"]=$room[0]->Length;
            $roomN["models"]=$models;
            $roomN["door"]=$door;
            $roomN["window"]=$windo;
            return response()->json([
                'state'=>True,
                'massege'=>'sucssfuly',
                'room'=>$roomN,
            ],200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'state'=>false,
                'massege'=>$th->getMessage()
            ],500);
        }
    }

}
