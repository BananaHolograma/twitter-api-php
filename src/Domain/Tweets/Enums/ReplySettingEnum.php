<?php

namespace Domain\Tweets\Enums;

enum ReplySettingEnum: string
{
    case EVERYONE = 'everyone';
    case MENTIONED_FOLLOWERS = 'mentioned_followers';
    case FOLLOWERS = 'followers';
}
