<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourApiRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;


class TourApiController extends Controller
{
    public function index(Travel $travel, TourApiRequest $request)
    {
        $tours = $travel->tours()
            ->when($request->has('priceFrom'), function ($query) use ($request) {
                return $query->where("price", '>=', $request->priceFrom * 100);
            })
            ->when($request->has('priceTo'), function ($query) use ($request) {
                return $query->where("price", '<=', $request->priceTo * 100);
            })
            ->when($request->has('dateFrom'), function ($query) use ($request) {
                return $query->whereDate("start_date", '>=', $request->dateFrom);
            })
            ->when($request->has('dateTo'), function ($query) use ($request) {
                return $query->whereDate("end_date", '>=', $request->dateTo);
            })
            ->when($request->has('orderBy') && $request->has('sortBy'), function ($query) use ($request) {
                return $query->orderBy($request->input('orderBy'), $request->input('sortBy'));
            })
            ->orderByDesc('start_date')
            ->paginate(15);
        return TourResource::collection($tours);
    }
}


