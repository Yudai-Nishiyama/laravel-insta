<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1;
    const USER_ROLE_ID = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    #to get all the posts of a user
    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
    }

    #to get all the follow ers of a user
    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
        // we can choose the column within the follow tables
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function isfollowed()
    {
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
        //Auth::user()->id is the follower_id
        //firstly, get all the followers of the User ($this->followers()). then, from that list,
        // search for the Auth user from the follower column (where('follower_id', Auth::user()->id))
    }

}
