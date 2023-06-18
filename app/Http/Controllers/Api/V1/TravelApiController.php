<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelCreateRequest;
use App\Http\Resources\GetTravelResource;
use App\Http\Resources\TravelResource;
use App\Models\Travel;

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

            /***
             * this is global function
             *  that implement inside provider and App\Helpers
             */

            if(!checkUserRole(config('userrole.admin')))
            {
              return response()->json([
                "message" => "You don't have access this action",
                "data" => "Access denied"
              ]);
            }

            $travels = Travel::create($request->validated());
            return new GetTravelResource($travels);
        } catch (\Exception $error) {
            dd($error->getMessage());
            return response()->json([
                "message" => "Something was wrong",
                "data" => ""
            ], 422);
        }
    }
}
