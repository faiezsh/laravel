<?php

namespace App\Http\Controllers;

use App\Models\RoomModel3D;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class recommendationSystem extends Controller
{
    //
    public static function Room($room)
    {
        $api_url="http\\:127.0.0.1";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
            ])->post($api_url, [
                'rate'=>  $room['rate'],
                   'length'=>$room['length'],
                   'widthe'=>$room['whidthe'],
                   'highe'=>$room['highe'] ,
                   'models'=>$room['models']
                ]);
            }

        public function generateRoom(Request $request)
            {
             try {
            //code...
            $roll=User::where('id',Auth::id())->value('roll');
            if ($roll!="engineer")
            {
                return response()->json([
                    'state'=>false,
                    'massege'=>'Access is denied'
                ]);
            }
            $models=array();
            $doors=$request->doors;
            $windows=$request->windows;
            foreach ($request->models as $m)
            {
                $model=Model3DController::get($m);
                array_push($models,$model);
            }

            if ($models==null)
            {

                $room=recommendationSystem::reqEngenir($request->highe,$request->whidth,$request->Length,Auth::id(),
                $request->doors,$request->windows,$request->color);
                if ($room!=false)
                {
                    return response()->json($room);
                }
            }
            $api_url="http\\:127.0.0.1";
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
                ])->post($api_url, [
                    'length'=>$request->highe,
                    'widthe'=>$request->whidth,
                    'highe'=> $request->length,
                    'models'=>$models,
                    'doors'=>$doors,
                    'windows'=>$windows
                ]);
                return response()->json([
                    'state'=>true,
                    'massege'=>'sucssfull',
                    'room'=>$response
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json([
                    'state'=>false,
                    'massege'=>$th->getMessage()
                ]);
            }
        }

        public function reqEngenir($h,$w,$l,$id,$doors,$windows,$color)
        {

            $room=array();
            $roomDesign=RoomModel3D::where('highe',$h)->where('whidth',$w)->where('Length',$l)
            ->where('userId',$id)->where('rate','>=',5)->get(['id','price','rate','color']);
            if ($roomDesign!=null)
            { $rate=0; $roomid=0;
                $room=array();
            foreach ($roomDesign as $r)
             {
                $windos=WindoController::Ok($r->id,$windows);
                $doorss=DoorController::Ok($r->id,$doors);
                if ($windos==true && $doorss==true)
                {
                    if ($r['rate'] > $rate)
                    {
                        $rate=$r['rate'];
                        $roomid=$r['id'];
                    }
            }
        }
           $models=DimensionsOfTheModelController::showDesign($roomid);
           $room["color"]=$color;
           $room["highe"]=$h;
           $room["width"]=$w;
           $room["Length"]=$l;
           $room["models"]=$models;
           $room["door"]=$doors;
           $room["window"]=$windows;
           return $room;
    }
    return false;
   }


}
