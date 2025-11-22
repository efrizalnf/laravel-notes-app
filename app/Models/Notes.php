<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Notes extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = "notes";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'title',
        'content',
        'image_path',
    ];
}
