<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    use HasFactory;
    protected $table="orders";
    protected $fillable = [
        'name','size','color','quality','price','user_id','payment','product_id'
    ];
    static function order($name,$size,$color,$quality,$price,$user_id,$payment,$product_id){
        $order= new orders();
        $order->name=$name;
        $order->size=$size;
        $order->color=$color;
        $order->quality=$quality;
        $order->price=$price;
        $order->user_id=$user_id;
        $order->payment=$payment;
        $order->product_id=$product_id;
        $order->save();
        return $order;
    }
}
