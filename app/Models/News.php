<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = "news";
    public $timestamps = false;
    const CREATED_AT = null; 
    protected $fillable = [
        'id',
        'body',
    ];

    
}
