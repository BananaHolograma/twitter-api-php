<?php

namespace Domain\Tweets\DataTransferObjects;

use Domain\Tweets\Enums\ReplySettingEnum;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\Data;

class UpsertTweetData extends Data
{
    public function __construct(
        public ?int $id,
        public ?array $replying_to,
        public ?int $reply_to_tweet_id,
        public string $text,
        public ?string $lang = 'en',
        public ?ReplySettingEnum $reply_settings = ReplySettingEnum::EVERYONE,
        public ?bool $possibly_sensitive = false,
        public ?array $visible_for = []
    ) {
    }

    public static function rules(): array
    {
        return [
            'id' => ['nullable', 'exists:tweets,id'],
            'replying_to' => ['present|array'],
            'replying_to.*' => ['exists:users,id'],
            'reply_to_tweet_id' => ['nullable', 'exists:tweets,id'],
            'text' => ['required', 'string', 'min:1', 'max:140'],
            'lang' => ['in:es,en'],
            'reply_settings' => [new Enum(ReplySettingEnum::class)],
            'possibly_sensitive' => 'boolean',
            'visible_for' => ['present|array'],
            'visible_for.*' => ['exists:users,id'],
        ];
    }
}
