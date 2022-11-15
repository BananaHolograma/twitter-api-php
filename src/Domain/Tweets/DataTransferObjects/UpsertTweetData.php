<?php

namespace Domain\Tweets\DataTransferObjects;

use Domain\Tweets\Enums\ReplySettingEnum;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\Data;

class UpsertTweetData extends Data
{
    public function __construct(
        public ?string $id,
        public string $text,
        public ReplySettingEnum $reply_settings = ReplySettingEnum::EVERYONE,
        public bool $possibly_sensitive = false,
        public ?array $visible_for = null
    ) {
    }

    public static function rules(): array
    {
        return [
            'id' => ['nullable', 'exists:users,id'],
            'text' => ['required', 'string', 'min:1', 'max:140'],
            'reply_settings' => [new Enum(ReplySettingEnum::class)],
            'possibly_sensitive' => 'boolean',
            'visible_for.*' => ['nullable', 'exists:users,id'],
        ];
    }
}
