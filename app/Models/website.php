<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class website extends Model
{
    use HasFactory;

    protected $table="website";
    protected $fillable=['title','description','link','number','email','created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];
    static function data(){
        return self::all();
    }
}
