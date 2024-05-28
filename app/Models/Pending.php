<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pending extends Model
{
    use HasFactory;

    protected $table = 'pendencies';

    protected $guarded = [
        'id',
    ];
}
