<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'restock_id',
        'sparepart_id',
        'qty',
        'harga_beli',
    ];

    public function restock()
    {
        return $this->belongsTo(Restock::class);
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}
