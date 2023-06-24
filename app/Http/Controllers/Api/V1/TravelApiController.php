<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelCreateRequest;
use App\Http\Requests\UpdateTravelRequest;
use App\Http\Resources\GetTravelResource;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use Illuminate\Http\Request;

class TravelApiController extends Controller
{
    public function index()
    {
        $travels =  Travel::where("is_public", true)->paginate(15);
        return TravelResource::collection($travels);
    }

    public function create(StoreTravelCreateRequest $request)
    {
        try {


            $travels = Travel::create($request->validated());
            return new GetTravelResource($travels);
        } catch (\Exception $error) {
            return response()->json([
                "message" => "Something was wrong",
                "data" => ""
            ], 422);
        }
    }

    public function update(Travel $travel,UpdateTravelRequest $request)
    {

        try {
            $travel->update($request->all());
            return new GetTravelResource($travel);

        } catch (\Exception $error) {
            return response()->json([
                "message" => "Something was wrong",
                "data" => ""
            ], 422);
        }



    }
}
