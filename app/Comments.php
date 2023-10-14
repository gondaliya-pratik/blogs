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
        'comment',
    ];
}
