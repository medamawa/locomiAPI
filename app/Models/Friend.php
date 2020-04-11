<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Friend extends Model
{
    use Notifiable;

    protected $primaryKey = [
        'following_id',
        'followed_id',
    ];

    protected $fillable = [
        'following_id',
        'followed_id',
    ];

    public $timestamps = false;
    public $incrementing = false;
}
