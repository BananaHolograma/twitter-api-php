<?php

namespace App\Models;

use App\Events\TweetCreatedEvent;
use App\Traits\HasSnowflakeAsPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tweet extends Model
{
    use HasSnowflakeAsPrimaryKey, HasFactory;

    protected $fillable = [
        'author_id', 'in_reply_to_author_id',
        'conversation_id', 'text', 'lang',
        'possibly_sensitive', 'source',
        'reply_settings',
    ];

    protected $guarded = [
        'edit_history_tweet_ids',
        'edit_controls',
        'withheld',
    ];

    protected $casts = [
        'edit_history_tweet_ids' => 'array',
        'edit_controls' => 'array',
        'reply_settings' => 'array',
        'possibly_sensitive' => 'boolean',
        'withheld' => 'array',
    ];

    protected $dispatchesEvents = [
        'created' => TweetCreatedEvent::class,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function authorReplied(): BelongsTo
    {
        return $this->belongsTo(User::class, 'in_reply_to_author_id', 'id');
    }

    public function originalConversation(): BelongsTo
    {
        return $this->belongsTo(Tweet::class, 'conversation_id', 'id');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_tweet_likes')->withTimestamps();
    }

    public function metrics(): HasOne
    {
        return $this->hasOne(TweetMetrics::class);
    }
}
