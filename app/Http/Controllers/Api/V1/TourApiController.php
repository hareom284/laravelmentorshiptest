<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\TourApiRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;

/**
 * @group Travel management
 *
 * APIs for managing tours
 */
class TourApiController extends Controller
{
    /***************
     *
     *
     * Get all tour list with filtering
     *
     * @bodyParam priceFrom string The price of the travel.
     * @bodyParam priceTo string The price of the travel.
     * @bodyParam dateFrom date The price of the travel.
     * @bodyParam dateTo date The price of the travel.
     * @bodyParam orderBy and sortBy by price list of travel
     *    @response   {
     *    "data": [
     *        {
     *            "id": "997c27dc-2281-43bd-8bdb-7cf47713e8e1",
     *            "travel_id": "997c27dc-205a-4fad-923b-cb8cdfba3089",
     *            "name": "Quia incidunt beatae dolores non animi aut recusandae.",
     *            "start_date": "2023-06-24",
     *            "end_date": "2023-06-30",
     *            "price": "394.20"
     *        },
     *        {
     *            "id": "997c27dc-2367-4f93-870a-cb4315424a40",
     *            "travel_id": "997c27dc-205a-4fad-923b-cb8cdfba3089",
     *            "name": "Est nobis qui voluptas unde et. Ut consequatur ipsa dignissimos id. Id ill",
     *            "start_date": "2023-06-24",
     *            "end_date": "2023-06-28",
     *            "price": "303.47"
     *        },
     *        {
     *            "id": "997c27dc-23fe-48be-bde4-6df0b5bc8f57",
     *            "travel_id": "997c27dc-205a-4fad-923b-cb8cdfba3089",
     *            "name": "Reiciendis optio velit et facere. Quas modi dolores velit ipsum aspe",
     *            "start_date": "2023-06-24",
     *            "end_date": "2023-07-04",
     *            "price": "498.52"
     *        },
     *        {
     *            "id": "997c27dc-2496-4968-ae53-52ddb6ac001f",
     *            "travel_id": "997c27dc-205a-4fad-923b-cb8cdfba3089",
     *            "name": "Repudiandae aut exercitationem numquam quisquam..",
     *            "start_date": "2023-06-24",
     *            "end_date": "2023-06-30",
     *            "price": "488.41"
     *        }
     *    ],
     *    "links": {
     *        "first": "http:\/\/laravelmentorshiptest.test\/api\/v1\/travels\/prof-stella-kuhn\/tours?page=1",
     *        "last": "http:\/\/laravelmentorshiptest.test\/api\/v1\/travels\/prof-stella-kuhn\/tours?page=1",
     *        "prev": null,
     *        "next": null
     *    },
     *    "meta": {
     *        "current_page": 1,
     *        "from": 1,
     *        "last_page": 1,
     *        "links": [
     *            {
     *                "url": null,
     *                "label": "&laquo; Previous",
     *                "active": false
     *            },
     *            {
     *                "url": "http:\/\/laravelmentorshiptest.test\/api\/v1\/travels\/prof-stella-kuhn\/tours?page=1",
     *                "label": "1",
     *                "active": true
     *            },
     *            {
     *                "url": null,
     *                "label": "Next &raquo;",
     *                "active": false
     *            }
     *        ],
     *        "path": "http:\/\/laravelmentorshiptest.test\/api\/v1\/travels\/prof-stella-kuhn\/tours",
     *        "per_page": 15,
     *        "to": 7,
     *        "total": 7
     *    }
     *}
     *
     **************/
    public function index(Travel $travel, TourApiRequest $request)
    {
        $tours = $travel->tours()
            ->when($request->has('priceFrom'), function ($query) use ($request) {
                return $query->where('price', '>=', $request->priceFrom * 100);
            })
            ->when($request->has('priceTo'), function ($query) use ($request) {
                return $query->where('price', '<=', $request->priceTo * 100);
            })
            ->when($request->has('dateFrom'), function ($query) use ($request) {
                return $query->whereDate('start_date', '>=', $request->dateFrom);
            })
            ->when($request->has('dateTo'), function ($query) use ($request) {
                return $query->whereDate('end_date', '>=', $request->dateTo);
            })
            ->when($request->has('orderBy') && $request->has('sortBy'), function ($query) use ($request) {
                return $query->orderBy($request->input('orderBy'), $request->input('sortBy'));
            })
            ->orderByDesc('start_date')
            ->paginate(15);

        return TourResource::collection($tours);
    }

    /********
     * create tour according to travel
     *
     * @authenticated
     *
     * @response {
     *  "data": {
     *  "id": "997c032f-e0fe-434b-af6a-dfef79ab7707",
     *  "travel_id": "997c02f9-e78a-4d7e-89fc-03f5414acb9b",
     *  "name": "road to bagon",
     *  "start_date": "2023-06-19",
     *  "end_date": "2023-06-29",
     *  "price": "999.00"
     *}
     * }
     */
    public function create(Travel $travel, StoreTourRequest $request)
    {

        $tour = $travel->tours()->create($request->all());

        return new TourResource($tour);
    }
}
