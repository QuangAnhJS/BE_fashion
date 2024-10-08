<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    use HasFactory;
    protected $table = "address";
    protected $fillable = ['thanhpho', 'quanhuyen', 'xaphuong', 'chitiet', 'nguoinhan', 'sdt'];
    static function address($thanhpho, $quanhuyen,$xaphuong, $chitiet,$nguoinhan,$id,$sdt)
    {
        $address = new address();
        $address->thanhpho=$thanhpho;
        $address->quanhuyen=$quanhuyen;
        $address->xaphuong=$xaphuong;
        $address->chitiet=$chitiet;
        $address->nguoinhan=$nguoinhan;
        $address->order_id=$id;
        $address->sdt=$sdt;
        $address->save();
    }
}
