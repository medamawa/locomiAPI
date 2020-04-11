<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['id'] = Uuid::uuid4()->toString();
    }

    protected $fillable = [
        'screen_name',
        'name',
        'profile_image',
        'email',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function followers()
    {
        return $this->belongsToMany(self::class, 'friends', 'followed_id', 'following_id');
    }

    public function follows()
    {
        return $this->belongsToMany(self::class, 'friends', 'following_id', 'followed_id');
    }

    public function getAllUsers(String $user_id)
    {
        // 全てのユーザーを返す、なおログインユーザーは除く
        if ($user_id) {
            return $this->Where('id', '<>', $user_id)->get();
        } else {
            return $this->get();
        }
    }

    public function getUser(String $user_id)
    {
        // 指定したユーザーを返す
        return $this->Where('id', '=', $user_id)->get();
    }

    public function getAllFollows(String $user_id)
    {
        return $this->follows()->Where('following_id', $user_id)->select('id')->get();
    }

    public function getAllFollowers(String $user_id)
    {
        return $this->followers()->Where('followed_id', $user_id)->select('id')->get();
    }

    public function follow(String $user_id)
    {
        return $this->follows()->attach($user_id);
    }

    public function unfollow(String $user_id)
    {
        return $this->follows()->detach($user_id);
    }

    public function isFollowing(String $user_id)
    {
        return (boolean) $this->follows()->where('followed_id', $user_id)->first(['id']);
    }

    public function isFollowed(String $user_id)
    {
        return (boolean) $this->followers()->where('following_id', $user_id)->first(['id']);
    }

    public function updateProfile(Array $params)
    {
        if (isset($params['screen_name'])) {
            $this->where('id', $this->id)->update([
                'screen_name' => $params['screen_name'],
            ]);
        }
        if (isset($params['name'])) {
            $this->where('id', $this->id)->update([
                'name' => $params['name'],
            ]);
        }
        if (isset($params['email'])) {
            $this->where('id', $this->id)->update([
                'email' => $params['email'],
            ]);
        }
        
        return ;
    }
}
