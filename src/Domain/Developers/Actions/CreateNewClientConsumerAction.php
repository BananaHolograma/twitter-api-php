<?php

namespace Domain\Developers\Actions;

use Domain\Developers\DataTransferObjects\ClientConsumerData;
use Domain\Developers\Models\Client;
use Laravel\Passport\ClientRepository;

class CreateNewClientConsumerAction
{
    public function __construct(private readonly ClientRepository $clientRepository)
    {
    }

    public function execute(ClientConsumerData $data): Client
    {
        return $this->clientRepository->create(
            $data->user->id,
            $data->name,
            $data->redirect_uri,
            null,
            true
        );
    }
}
