<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'email',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'photo_in',
        'photo_out',
        'location_in',
        'location_out',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
