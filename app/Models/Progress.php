<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $table = 'progress';
    protected $fillable = ['title', 'author', 'genre', 'status', 'progress', 'rating' ];
}
