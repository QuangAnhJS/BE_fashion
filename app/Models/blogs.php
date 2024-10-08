<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blogs extends Model
{
    use HasFactory;
    protected $table="blog";
    protected $fillable=['title','description'];
    static public function createBlog($title,$description){
        $table= new blogs();
        $table->title=$title;
        $table->description=$description;
        $table->save();
        return $table;
    }
    public function blog_img(){
        return $this->hasMany(blog_img::class,'blog_id','id');
    }
}

