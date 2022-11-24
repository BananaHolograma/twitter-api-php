<?php

namespace Domain\Developers\DataTransferObjects;

use Domain\Developers\Models\Client;
use Spatie\LaravelData\Data;

class ClientBearerTokenData extends Data
{
    public function __construct(
        public Client $client,
        public string $access_token,
        public int $expires_in,
        public array $scopes
    ) {
    }
}
