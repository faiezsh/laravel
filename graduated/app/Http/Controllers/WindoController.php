<?php

namespace App\Http\Controllers;

use App\Models\Windo;
use Illuminate\Http\Request;

class WindoController extends Controller
{
    //

    public static function addWindo($roomId,$pos,$width,$location)
    {
        try {
            //code...
            $Windo=Windo::create([
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

    public static function getWindo($id)
    {
        $Windo=Windo::where('roomId',$id)->get([
            'pos',
            'width',
            'location'
        ]);
        return $Windo;
    }

    public static function Ok($id,$Window)
    {
        $y=true;
        for ($i=0;$i<count($Window);$i++)
        {
            $Window=Windo::where('roomId',$id)->where('pos',$Window[$i]['pos'])
            ->where('width',$Window[$i]['width'])
            ->where('location',$Window[$i]['location'])
            ->get(['pos','width','location']);
            if ($Window==null)
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
