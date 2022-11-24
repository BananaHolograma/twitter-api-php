<?php

namespace Domain\Developers\DataTransferObjects;

use Domain\Shared\Models\User;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class ClientConsumerData extends Data
{
    public function __construct(
        public User $user,
        public string $name,
        public string $redirect_uri
    ) {
    }

    public static function fromRequest(Request $request)
    {
        return new self(
            $request->user('api'),
            $request->name,
            route('api.developers-portal.callback'),
        );
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'max:191'],
        ];
    }
}
