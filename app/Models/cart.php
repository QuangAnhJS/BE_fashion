<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
    protected $fillable = [
        "quanlity",
        "price",
        "product_id",
        "user_id",
        "size",
        "color"
    ];
    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
    public function product()
    {
        return   $this->hasOne(product::class,"id","product_id");
    }
    static public function addCart($quanlity, $price, $product_id,$user_id,$size,$color)
    {
        $addCart = new cart;
        $addCart->quanlity = $quanlity;
        $addCart->price = $price;
        $addCart->product_id = $product_id;
        $addCart->user_id = $user_id;
        $addCart->size = $size;
        $addCart->color = $color;
       
        $addCart->save();
    }
}
