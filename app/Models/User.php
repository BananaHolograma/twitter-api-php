<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HasSnowflakeAsPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasSnowflakeAsPrimaryKey, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'description',
        'url',
        'location',
        'protected',
        'email',
        'password',
    ];

    protected $guarded = ['verified_at'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'protected' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['verified_at'];

    public function tweets(): HasMany
    {
        return $this->hasMany(Tweet::class, 'author_id', 'id');
    }

    public function likedTweets(): BelongsToMany
    {
        return $this->belongsToMany(Tweet::class,  'user_tweet_likes')->withTimestamps();
    }
}
