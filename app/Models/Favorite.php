<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Favorite extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['id'] = Uuid::uuid4()->toString();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isFavorite(String $user_id, String $comic_id)
    {
        return (Boolean) $this->where('user_id', $user_id)->where('comic_id', $comic_id)->first();
    }

    public function storeFavorite(String $user_id, String $comic_id)
    {
        $this->user_id = $user_id;
        $this->comic_id = $comic_id;
        $this->save();

        return ;
    }

    public function destroyFavorite(String $user_id, String $comic_id)
    {
        return $this->where('user_id', $user_id)->where('comic_id', $comic_id)->delete();
    }

    public function getFavorites(String $comic_id)
    {
        return $this->where('comic_id', $comic_id)->get();
    }
}
