<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $table="products";
    public function Img(){
        return   $this->hasOne(Img::class);
    }
    public function Sizes(){
        return   $this->hasMany(Sizes::class);
    }
    public function colors(){
        return   $this->hasMany(colors::class);
    }
    
    public function Category(){
        return $this->belongsTo(category::class,'category_id', 'id');
    }
    public function cart(){
        return   $this->hasMany(cart::class);
    }
    static function getID($product_id){
        return self::find($product_id);
    }
}
