<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DimensionsOfTheModel extends Model
{
    use HasFactory;
    protected $table='DimensionsOfTheModel';
    public $timestamps=true;



    protected $dates = ['deleted_at'];
    protected $fillable = array(
        'modelId',
        'roomModelId',
        'x',
        'y',
        'z',
        'scale',
        'rotate',
    );
}
