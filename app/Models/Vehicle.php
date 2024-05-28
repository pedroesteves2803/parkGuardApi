<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'entry_times' => 'datetime',
        'departure_times' => 'datetime',
    ];

    public function pendings()
    {
        return $this->hasMany(Pending::class);
    }
}
