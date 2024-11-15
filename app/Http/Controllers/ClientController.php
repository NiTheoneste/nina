<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Http\Resources\ClientCollection;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    public function index(Request $request): ClientCollection
    {
        $clients = Client::all();

        return new ClientCollection($clients);
    }

    public function store(ClientStoreRequest $request): ClientResource
    {
        $client = Client::create($request->validated());

        return new ClientResource($client);
    }

    public function show(Request $request, Client $client): ClientResource
    {
        return new ClientResource($client);
    }

    public function update(ClientUpdateRequest $request, Client $client): ClientResource
    {
        $client->update($request->validated());

        return new ClientResource($client);
    }

    public function destroy(Request $request, Client $client): Response
    {
        $client->delete();

        return response()->noContent();
    }
}
