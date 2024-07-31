<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomModel3D extends Model
{
    use HasFactory;
        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'room_model3d';
    public $timestamps = true;
    protected $fillable = [
         'companyId',
         'userId',
         'roomModelName',
         'price',
         'highe' ,
         'whidth',
         'Length',
         'rate',
         'color'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<integer, integer>
     */
}
