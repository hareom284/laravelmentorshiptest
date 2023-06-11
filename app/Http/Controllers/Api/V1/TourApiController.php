<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Models\Travel;


class TourApiController extends Controller
{
    public function index(Travel $travel)
    {

        $tours = $travel->tours()
            ->orderByDesc('start_date')
            ->paginate(15);
        return TourResource::collection($tours);
    }
}
