<?php

namespace App\Http\Controllers;

use App\Models\door;
use Illuminate\Http\Request;

class DoorController extends Controller
{
    //

    public  static function addDoor($roomId,$pos,$width,$location)
    {
        try {
            //code...
            $door=door::create([
                'roomId'=>$roomId,
                'pos'=>$pos,
                'width'=>$width,
                'location'=>$location
            ]);
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }

    public static function getDoor($id)
    {
        $door=door::where('roomId',$id)->get([
            'pos',
            'width',
            'location'
        ]);
        return $door;
    }

    public static function Ok($id,$door)
    {
        $y=true;
        for($i=0;$i<count($door);$i++)
        {

            $doors=door::where('roomId',$id)->where('pos',$door[$i]['pos'])
            ->where('width',$door[$i]['width'])
            ->where('location',$door[$i]['location'])
            ->get(['pos','width','location']);
            if ($doors==null)
            {
                $y=false;
                break;
            }
        }
         if ($y)
         {
            return true;
         }
         return false;
    }
}
