<?php

namespace App\Http\API\Developers\Controllers;

use App\Http\Controllers\Controller;
use Domain\Developers\Actions\CreateNewClientConsumerAction;
use Domain\Developers\DataTransferObjects\ClientConsumerData;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    public function __construct(
        private readonly CreateNewClientConsumerAction $createNewClientConsumerAction
    ) {
    }

    public function createDeveloperClient(Request $request)
    {
        $data = ClientConsumerData::fromRequest($request);
        $result = $this->createNewClientConsumerAction->execute($data);

        return $result->toJson();
    }

    public function handleClientCallback()
    {
        abort(404);
    }
}
