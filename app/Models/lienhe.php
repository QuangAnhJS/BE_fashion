<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lienhe extends Model
{
    use HasFactory;
    protected $table="lienhe";
    static function data(){
        return self::all();
    }
}
