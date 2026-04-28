<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_booking',
        'user_id',
        'plat_nomor',
        'kendaraan',
        'keluhan',
        'tanggal',
        'jam',
        'status',
        'is_reviewed',
        'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'is_reviewed' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->hasOne(PosTransaction::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
