<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelResource;
use App\Models\Travel;

class TravelApiController extends Controller
{
    public function index()
    {
        $travels =  Travel::where("is_public", true)->paginate(15);
        return TravelResource::collection($travels);
    }
}
