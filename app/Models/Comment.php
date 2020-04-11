<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Comment extends Model
{
    use SoftDeletes;
    use Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['id'] = Uuid::uuid4()->toString();
    }

    protected $fillable = [
        'text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getComments(String $comic_id)
    {
        return $this->with('user')->where('comic_id', $comic_id)->get();
    }

    public function commentStore(String $user_id, Array $data)
    {
        $this->user_id = $user_id;
        $this->comic_id = $data['comic_id'];
        $this->text = $data['text'];
        $this->save();

        return ;
    }

    public function commentDestroy(String $user_id, String $comment_id)
    {
        return $this->where('user_id', $user_id)->where('id', $comment_id)->delete();
    }
}
