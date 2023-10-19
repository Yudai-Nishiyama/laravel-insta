<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Post extends Model
{
    use HasFactory, SoftDeletes;

    #post belongs to a user
    #to get the owner of the post
    public function user()
    {
        return $this->belongsTo(user::class)->withTrashed();
    }

    #many to many relation is not support in laravel so we need a pevatable
    #pevatable us connect to 2 tables like a HUB

    #to get the ccategories under a post
    public function categoryPost()
    {
        return $this->hasMany(CategoryPost::class);
    }

    #to get the all comments of a post
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    # to get the likes of a post
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    #returns True if the Auth user already likes the post
    //exist() will show true or false.
    public function isliked()
    {
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
    }
}
