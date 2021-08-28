<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'active',
        'order'
    ];

    protected $table = "bank";

    protected $casts = [
        'active' => 'boolean',
    ];

}
