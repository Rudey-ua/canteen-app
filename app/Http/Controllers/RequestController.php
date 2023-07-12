<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request\StoreRequest;
use App\Http\Requests\Request\UpdateRequest;
use App\Http\Resources\Request\RequestCollection;
use App\Http\Resources\Request\RequestResource;
use App\Models\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index(): JsonResponse
    {
        $request = Request::all();

        return response()->json([
            "requests" => new RequestCollection($request)
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        return response()->json( new RequestResource($request));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->getAuthIdentifier();

        $Request = Request::create($data);

        return response()->json(new RequestResource($Request), 201);
    }

    public function update(UpdateRequest $updateRequest, Request $request): JsonResponse
    {
        $request->update($updateRequest->validated());

        return response()->json(new RequestResource($request));
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->delete();
        return response()->json(null, 204);
    }

    public function getRestaurantRequests($restaurant_id): JsonResponse
    {
        $request = Request::where('restaurant_id', $restaurant_id)->get();

        return response()->json([
            'requests' => new RequestCollection($request),
        ]);
    }
}
