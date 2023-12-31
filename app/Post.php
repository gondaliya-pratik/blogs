<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
	use SoftDeletes;

	protected $table = 'posts';

    protected $fillable = [
    	'user_id',
        'title',
        'description',
        'image',
        'is_published',
    ];

    public function likes() 
    {
    	return $this->hasMany(\App\Likes::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Comments::class, 'post_id')->whereNull('parent_id');
    }

    public function total_comments_count()
    {
        return $this->hasMany(\App\Comments::class, 'post_id');
    }


}
