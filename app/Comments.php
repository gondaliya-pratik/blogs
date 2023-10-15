<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table = 'comments';

    protected $fillable = [
    	'user_id',
        'post_id',
        'parent_id',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(\App\Comments::class, 'parent_id');
    }
}
