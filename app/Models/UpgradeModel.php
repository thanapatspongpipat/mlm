<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpgradeModel extends Model
{
    use HasFactory;

    protected $table = "upgrade";

    protected $fillable = [
        'user_id',
        'package_id',
        'sent_type'
    ];


}
