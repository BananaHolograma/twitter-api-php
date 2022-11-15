<?php

namespace Domain\Tweets\Models;

use App\Events\Tweets\TweetCreatedEvent;
use App\Events\Tweets\TweetUpdatingEvent;
use Carbon\Carbon;
use Domain\Shared\Models\BaseEloquentModel;
use Domain\Shared\Models\User;
use Domain\Shared\Traits\HasSnowflakeAsPrimaryKey;
use Domain\Tweets\Enums\ReplySettingEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tweet extends BaseEloquentModel
{
    use HasSnowflakeAsPrimaryKey;

    protected $fillable = [
        'author_id', 'in_reply_to_author_id',
        'conversation_id', 'text', 'lang',
        'possibly_sensitive', 'source',
        'reply_settings', 'visible_for',
    ];

    protected $guarded = [
        'id',
        'edit_history_tweet_ids',
        'edit_controls',
        'withheld',
    ];

    protected $casts = [
        'edit_history_tweet_ids' => 'array',
        'edit_controls' => 'array',
        'reply_settings' => ReplySettingEnum::class,
        'possibly_sensitive' => 'boolean',
        'visible_for' => 'array',
        'withheld' => 'array',
    ];

    protected $appends = [
        'is_editable',
    ];

    protected $dispatchesEvents = [
        'created' => TweetCreatedEvent::class,
        'updating' => TweetUpdatingEvent::class,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Tweet::class, 'in_reply_to_author_id', 'author_id');
    }

    public function metrics(): HasOne
    {
        return $this->hasOne(TweetMetrics::class);
    }

    public function authorReplied(): BelongsTo
    {
        return $this->belongsTo(User::class, 'in_reply_to_author_id', 'id');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_tweet_likes')->withTimestamps();
    }

    public function getIsEditableAttribute(): bool
    {
        return isset($this->edit_controls) &&
            $this->edit_controls['edits_remaining'] > 0 &&
            Carbon::parse($this->edit_controls['editable_until'])->diffInMinutes(now()) > 30;
    }
}
