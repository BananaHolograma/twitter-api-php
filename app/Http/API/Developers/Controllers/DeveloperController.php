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
        $client = $this->createNewClientConsumerAction->execute($data)->makeVisible('secret');
        $token = $data->user->createToken("{$data->user->username}:{$data->name}")->accessToken;

        return response()->json(compact('client', 'token'));
    }

    public function handleClientCallback()
    {
        abort(404);
    }
}
