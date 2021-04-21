<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['is_bookmarked', 'is_liked'];

    public static function getImageDirectory()
    {
        return "/images/posts/";
    }

    public static function getPublicDirectory()
    {
        return public_path() . static::getImageDirectory();
    }

    public static function getFileName($image)
    {
        return Str::random(15) . '.' . $image->getClientOriginalExtension();
    }

    public function getCategoriesTitleAttribute()
    {
        return $this->categories->pluck("title");
    }

    public function getImageAttribute()
    {
        return '/images/posts/' . $this->attributes['image'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function parentComments()
    {
        return $this->comments()->whereNull('comment_id');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks'); // esme table post_user nist bookmark
    }

    public function getIsBookmarkedAttribute()
    {
        return $this->bookmarks()->where('user_id', optional(request()->user())->id)->exists();
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes'); // esme table post_user nist bookmark
    }

    public function getIsLikedAttribute()
    {
        return $this->likes()->where('user_id', optional(request()->user())->id)->exists();
    }
}