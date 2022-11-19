<?php

namespace Domain\Shared\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Database\Factories\Shared\UserFactory;
use Domain\Shared\Traits\HasSnowflakeAsPrimaryKey;
use Domain\Tweets\Models\Tweet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasSnowflakeAsPrimaryKey, HasFactory, HasApiTokens, Notifiable;

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

    protected $guarded = ['id', 'verified_at'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'integer',
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
        return $this->belongsToMany(Tweet::class, 'user_tweet_likes')->withTimestamps();
    }

    public function mutedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'mutes', 'muted_user_id', 'user_id', 'id')->withTimestamps();
    }

    public function blockedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'blocks', 'blocked_user_id', 'user_id', 'id')->withTimestamps();
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
