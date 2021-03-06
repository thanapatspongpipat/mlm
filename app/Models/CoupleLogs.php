<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoupleLogs extends Model
{
    use HasFactory;
    protected $table = "couple_logs";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
