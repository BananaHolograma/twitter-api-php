<?php

namespace Domain\Shared\Traits;

trait HasSnowflakeAsPrimaryKey
{
    //https://itnext.io/how-to-use-twitter-snowflake-ids-for-your-database-primary-keys-in-laravel-763a98e78466
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (is_null($model->getKey())) {
                $model->{$model->getKeyName()} = resolve('snowflake')->id();
            }
        });
    }

    public function getIncrementing()
    {
        return false;
    }
}
