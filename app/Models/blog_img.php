<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blog_img extends Model
{
    use HasFactory;
    protected $table="blog_img";
    public function blog(){
        return $this->belongsTo(blogs::class,'blog_id');
    }
}

