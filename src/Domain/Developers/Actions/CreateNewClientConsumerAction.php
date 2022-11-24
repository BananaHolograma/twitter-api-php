<?php

namespace Domain\Developers\Actions;

use Domain\Developers\DataTransferObjects\ClientBearerTokenData;
use Domain\Developers\DataTransferObjects\ClientConsumerData;
use Laravel\Passport\ClientRepository;

class CreateNewClientConsumerAction
{
    public function __construct(private readonly ClientRepository $clientRepository)
    {
    }

    public function execute(ClientConsumerData $data): ClientBearerTokenData
    {
        $this->ensureClientCanBeCreated($data);

        $client = $this->clientRepository->create(
            $data->user->id,
            $data->name,
            $data->redirect_uri,
            null,
            true
        );

        $access_token = $data->user->createToken("{$data->user->username}:{$data->name}")->accessToken;

        return ClientBearerTokenData::from([
            'client' => $client->makeVisible('secret'),
            'access_token' => $access_token,
            'expires_in' => $data->user->token()->expires_at->diffInSeconds(now()),
            'scopes' => $data->user->token()->scopes,
        ]);
    }

    public function ensureClientCanBeCreated(ClientConsumerData $data): void
    {
    }
}
