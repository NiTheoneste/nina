<?php

namespace App\Http\Controllers;

use App\Http\Requests\DebtStoreRequest;
use App\Http\Requests\DebtUpdateRequest;
use App\Http\Resources\DebtCollection;
use App\Http\Resources\DebtResource;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DebtController extends Controller
{
    public function index(Request $request): DebtCollection
    {
        $debts = Debt::all();

        return new DebtCollection($debts);
    }

    public function store(DebtStoreRequest $request): DebtResource
    {
        $debt = Debt::create($request->validated());

        return new DebtResource($debt);
    }

    public function show(Request $request, Debt $debt): DebtResource
    {
        return new DebtResource($debt);
    }

    public function update(DebtUpdateRequest $request, Debt $debt): DebtResource
    {
        $debt->update($request->validated());

        return new DebtResource($debt);
    }

    public function destroy(Request $request, Debt $debt): Response
    {
        $debt->delete();

        return response()->noContent();
    }
}
