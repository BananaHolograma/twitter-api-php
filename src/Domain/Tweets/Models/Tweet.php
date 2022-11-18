<?php

namespace Domain\Tweets\Models;

use App\Events\Tweets\TweetCreatedEvent;
use Carbon\Carbon;
use Domain\Shared\Models\BaseEloquentModel;
use Domain\Shared\Models\User;
use Domain\Shared\Traits\HasSnowflakeAsPrimaryKey;
use Domain\Tweets\Enums\ReplySettingEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tweet extends BaseEloquentModel
{
    use HasSnowflakeAsPrimaryKey, SoftDeletes;

    protected $fillable = [
        'author_id', 'in_reply_to_author_id',
        'in_reply_to_tweet_id', 'edit_history_tweet_ids',
        'conversation_id', 'text', 'lang',
        'possibly_sensitive', 'source',
        'reply_settings', 'visible_for',
    ];

    protected $guarded = [
        'id',
        'withheld',
    ];

    protected $casts = [
        'edit_controls' => 'array',
        'edit_history_tweet_ids' => 'array',
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
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Tweet::class, 'in_reply_to_tweet_id', 'id')->whereNotNull('conversation_id');
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
            Carbon::parse($this->edit_controls['editable_until'])->greaterThan(now());
    }


    public function getEditHistoryAttribute(): Collection
    {
        if (isset($this->edit_history_tweet_ids) && count($this->edit_history_tweet_ids)) {
            return self::onlyTrashed()
                ->whereIn('id', $this->edit_history_tweet_ids)
                ->orderBy('deleted_at')
                ->get();
        }

        return Collection::empty();
    }
}
