<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Windo extends Model
{
    use HasFactory;

    protected $table = 'windos';
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = array(
        'roomId',
        'pos',
        'width',
        'location'
    );
}
