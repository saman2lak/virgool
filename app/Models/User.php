<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile',
        'bio',
        'username',
        'email_on_follow',
        'email_on_like',
        'email_on_replay',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    // Accessors & Mutators Laravel
    public function getProfileSrcAttribute()
    {
        $profileImage = Str::beforeLast($this->profile, '?');
        return $this->profile && File::exists(public_path('/profiles/' . $profileImage))
            ?
            '/profiles/' . $this->profile
            :
            '/image/avatar.jpg';
    }
    protected $appends = ['profile_src'];


    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function drafts()
    {
        return $this->hasMany(Draft::class);
    }

    public function follows()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'user_id');
    }

    public function getIsFollowsAttribute()
    {
        return $this->followers()->where('user_id', optional(request()->user())->id)->exists();
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Post::class, 'bookmarks');
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'likes');
    }
}