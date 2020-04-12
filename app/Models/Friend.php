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

    public function followedIds(String $user_id)
    {
        return $this->where('following_id', $user_id)->get('followed_id');
    }
}
