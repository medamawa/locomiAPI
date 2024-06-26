<?php

namespace App\Models;

use App\Models\Traits\HasGeometryAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Comic extends Model
{
    use SoftDeletes;
    use Notifiable;
    use HasGeometryAttributes;

    protected $keyType = 'string';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['id'] = Uuid::uuid4()->toString();
    }

    protected $fillable = [
        'location',
        'text',
        'image',
        'release',
    ];

    protected $geometries = ['location'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getPublicComics()
    {
        $comics = DB::select('SELECT id, user_id, X(location), Y(location), text, image, created_at, updated_at, altitude FROM comics WHERE deleted_at IS NULL ORDER BY created_at desc');
        
        return $comics;
    }

    public function getComic(String $comic_id)
    {
        $comic = DB::select('SELECT id, user_id, X(location), Y(location), text, image, created_at, updated_at, altitude FROM comics WHERE deleted_at IS NULL AND id = ? ORDER BY created_at desc', [$comic_id]);
        
        return $comic;
    }

    public function getUserComics(String $user_id)
    {
        $comic = DB::select('SELECT id, user_id, X(location), Y(location), text, image, created_at, updated_at, altitude FROM comics WHERE deleted_at IS NULL AND user_id = ? ORDER BY created_at desc', [$user_id]);
        
        return $comic;
    }

    public function getNearComics(String $lat, String $lng, String $distance)
    {
        $dis = $distance / 1000;
        $comics = DB::select('SELECT id, user_id, X(location), Y(location), text, image, created_at, updated_at, altitude, ( 6371 * acos( cos( radians(?) ) * cos( radians( X(location) ) ) * cos( radians( Y(location) ) - radians(?) ) + sin( radians(?) ) * sin( radians( X(location) ) ) ) ) AS distance FROM comics WHERE deleted_at IS NULL HAVING distance < ? ORDER BY distance LIMIT 0, 1000', [$lat, $lng, $lat, $dis]);

        return $comics;
    }

    public function getFollowsComics(String $user_id, Array $follow_ids)
    {
        //　ログインユーザーのIDとフォローしているユーザーのIDを結合する
        $follow_ids[] = $user_id;
        
        return $this->whereIn('user_id', $follow_ids)->get();
    }

    public function comicStore(String $user_id, Array $data)
    {
        $this->user_id = $user_id;
        $this->location = $data['location'];
        $this->text = $data['text'];
        if (isset($data['image'])) {
            $this->image = $data['image'];
        }
        $this->release = $data['release'];
        $this->altitude = $data['altitude'];
        $this->save();

        return ;
    }

    public function comicDestroy(String $user_id, String $comic_id)
    {
        return $this->where('user_id', $user_id)->where('id', $comic_id)->delete();
    }
}
