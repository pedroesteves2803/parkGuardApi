<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vehicle extends Model
{
    use Notifiable, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'entry_times' => 'datetime',
        'departure_times' => 'datetime',
    ];

    public function pendings()
    {
        return $this->hasMany(Pending::class);
    }

    public function routeNotificationForVonage($notification): string
    {
        return '5511935051520';
    }
}
