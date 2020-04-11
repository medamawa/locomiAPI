<?php

namespace App\Models;

use App\Models\Traits\HasGeometryAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Comic extends Model
{
    use SoftDeletes;
    use Notifiable;
    use HasGeometryAttributes;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['uuid'] = Uuid::uuid4()->toString();
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
}
