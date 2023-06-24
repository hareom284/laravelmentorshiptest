<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelCreateRequest;
use App\Http\Requests\UpdateTravelRequest;
use App\Http\Resources\GetTravelResource;
use App\Http\Resources\TravelResource;
use App\Models\Travel;

/**
 * @group Travel management
 *
 * APIs for managing travels
 */
class TravelApiController extends Controller
{
    /**
     * Get Travel public travels list
     *
     * @response   {
     * "data": [
     *  {
     *      "id": "997c1f88-9ef8-4908-85f4-a43bc627a4a0",
     *      "name": "Ms. Eliane Pfeffer",
     *      "slug": "ms-eliane-pfeffer",
     *      "description": "I like being that person, I'll come up: if not, I'll stay down here! It'll be no sort of thing.",
     *      "number_of_days": 98,
     *      "number_of_nights": 97
     *  },
     *  {
     *      "id": "997c1f88-a010-4ed4-a244-0271bcb4dda7",
     *      "name": "Adele Blanda",
     *      "slug": "adele-blanda",
     *      "description": "In the very tones of her little sister's dream. The long grass rustled at her hands, wondering if.",
     *      "number_of_days": 59,
     *      "number_of_nights": 58
     *  },
     *  {
     *      "id": "997c1f88-a11b-4ea1-ac31-6abb4e93705d",
     *      "name": "Conor Braun",
     *      "slug": "conor-braun",
     *      "description": "March Hare. 'Sixteenth,' added the Gryphon, 'she wants for to know when the White Rabbit, 'but it.",
     *      "number_of_days": 94,
     *      "number_of_nights": 93
     *  },
     *  {
     *      "id": "997c1f88-a239-4b6f-978c-1b28978b7938",
     *      "name": "Maxie Reichel",
     *      "slug": "maxie-reichel",
     *      "description": "Pat, what's that in about half no time! Take your choice!' The Duchess took her choice, and was.",
     *      "number_of_days": 70,
     *      "number_of_nights": 69
     *  },
     *  {
     *      "id": "997c1f88-a353-45d9-9f6e-60ba7ed20728",
     *      "name": "Darby Schaden DDS",
     *      "slug": "darby-schaden-dds",
     *      "description": "Majesty,' said Alice in a tone of great surprise. 'Of course it was,' he said. (Which he certainly.",
     *      "number_of_days": 61,
     *      "number_of_nights": 60
     *  },
     *  {
     *      "id": "997c1f88-a460-4254-8c67-7de5da06a627",
     *      "name": "Dr. Mike Wiza DVM",
     *      "slug": "dr-mike-wiza-dvm",
     *      "description": "There were doors all round her, calling out in a great thistle, to keep herself from being broken.",
     *      "number_of_days": 69,
     *      "number_of_nights": 68
     *  },
     *  {
     *      "id": "997c1f88-a56b-4d2d-a81e-d2a4fbf00c8a",
     *      "name": "Amara Glover DDS",
     *      "slug": "amara-glover-dds",
     *      "description": "Queen. 'I haven't the slightest idea,' said the Gryphon. 'The reason is,' said the King: 'however.",
     *      "number_of_days": 65,
     *      "number_of_nights": 64
     *  },
     *  {
     *      "id": "997c1f88-a675-4291-838a-2e8179aa95d9",
     *      "name": "Mr. Schuyler Kub",
     *      "slug": "mr-schuyler-kub",
     *      "description": "Alice hastily replied; 'only one doesn't like changing so often, you know.' He was an immense.",
     *      "number_of_days": 75,
     *      "number_of_nights": 74
     *  },
     *  {
     *      "id": "997c1f88-a7a0-4a6f-81b4-49cc466ead38",
     *      "name": "Alessandra Cummings",
     *      "slug": "alessandra-cummings",
     *      "description": "Alice again, in a sort of thing that would happen: '\"Miss Alice! Come here directly, and get ready.",
     *      "number_of_days": 48,
     *      "number_of_nights": 47
     *  },
     *  {
     *      "id": "997c1f88-a8c5-4fd3-891a-535b0eccd3cc",
     *      "name": "Prof. Sandrine Effertz IV",
     *      "slug": "prof-sandrine-effertz-iv",
     *      "description": "Alice, in a tone of great surprise. 'Of course it was,' the March Hare. 'Exactly so,' said the.",
     *      "number_of_days": 7,
     *      "number_of_nights": 6
     *  },
     *  {
     *      "id": "997c1f88-a9c8-4ab5-a75c-e73bf429b11e",
     *      "name": "Christelle Friesen",
     *      "slug": "christelle-friesen",
     *      "description": "He sent them word I had it written down: but I grow at a reasonable pace,' said the Queen, who had.",
     *      "number_of_days": 69,
     *      "number_of_nights": 68
     *  },
     *  {
     *      "id": "997c1f88-aade-4ca3-a652-173fba442740",
     *      "name": "Maymie Ratke",
     *      "slug": "maymie-ratke",
     *      "description": "IS that to be sure, this generally happens when you come and join the dance? Will you, won't you.",
     *      "number_of_days": 44,
     *      "number_of_nights": 43
     *  },
     *  {
     *      "id": "997c1f88-ac0f-4b70-a647-6a7ba8cf8e13",
     *      "name": "Prof. Vivianne Kshlerin",
     *      "slug": "prof-vivianne-kshlerin",
     *      "description": "YOU with us!\"' 'They were learning to draw, you know--' (pointing with his tea spoon at the Lizard.",
     *      "number_of_days": 25,
     *      "number_of_nights": 24
     *  },
     *  {
     *      "id": "997c1f88-ad5a-4ef1-90e6-4effb928f25b",
     *      "name": "Hassie Connelly",
     *      "slug": "hassie-connelly",
     *      "description": "I'm not used to say but 'It belongs to the door, and the Dormouse turned out, and, by the English.",
     *      "number_of_days": 25,
     *      "number_of_nights": 24
     *  },
     *  {
     *      "id": "997c1f88-ae53-4006-a75c-f15677dccee5",
     *      "name": "Orie Sporer",
     *      "slug": "orie-sporer",
     *      "description": "No, there were three little sisters--they were learning to draw, you know--' (pointing with his.",
     *      "number_of_days": 75,
     *      "number_of_nights": 74
     *  }
     *  ],
     *   "links": {
     *  "first": "http://laravelmentorshiptest.test/api/v1/travels?page=1",
     *  "last": "http://laravelmentorshiptest.test/api/v1/travels?page=2",
     *  "prev": null,
     *  "next": "http://laravelmentorshiptest.test/api/v1/travels?page=2"
     *  },
     *      "meta": {
     *  "current_page": 1,
     *  "from": 1,
     *  "last_page": 2,
     *  "links": [
     *      {
     *          "url": null,
     *          "label": "&laquo; Previous",
     *          "active": false
     *      },
     *      {
     *          "url": "http://laravelmentorshiptest.test/api/v1/travels?page=1",
     *          "label": "1",
     *          "active": true
     *      },
     *      {
     *          "url": "http://laravelmentorshiptest.test/api/v1/travels?page=2",
     *          "label": "2",
     *          "active": false
     *      },
     *      {
     *          "url": "http://laravelmentorshiptest.test/api/v1/travels?page=2",
     *          "label": "Next &raquo;",
     *          "active": false
     *      }
     *  ],
     *  "path": "http://laravelmentorshiptest.test/api/v1/travels",
     *  "per_page": 15,
     *  "to": 15,
     *  "total": 16
     * }
     *  }
     ***********/
    public function index()
    {
        $travels = Travel::where('is_public', true)->paginate(15);

        return TravelResource::collection($travels);
    }

    /**
     *  create Travel  travel
     *  for create travel
     *
     *  @authenticated
     *
     *  @header Content-Type application/json
     *
     *  @response {
     *   "data": {
     *    "name": "road-to-kadsdsdow-aabb",
     *    "is_public": 1,
     *    "description": "this is description where you start something",
     *    "number_of_days": 10,
     *    "slug": "road-to-kadsdsdow-aabb",
     *    "id": "997c2d52-9e5f-4f6b-920b-59d47ef0dd7a",
     *    "updated_at": "2023-06-24T05:55:53.000000Z",
     *    "created_at": "2023-06-24T05:55:53.000000Z"
     *     }
     *   }
     *     */
    public function create(StoreTravelCreateRequest $request)
    {
        try {

            $travels = Travel::create($request->validated());

            return new GetTravelResource($travels);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Something was wrong',
                'data' => '',
            ], 422);
        }
    }

    /**
     * update travel only for both editor and admin
     * this end
     *
     *  @authenticated
     *
     *    @response {
     *   "data": {
     *    "name": "road-to-kadsdsdow-aabb",
     *    "is_public": 1,
     *    "description": "this is description where you start something",
     *    "number_of_days": 10,
     *    "slug": "road-to-kadsdsdow-aabb",
     *    "id": "997c2d52-9e5f-4f6b-920b-59d47ef0dd7a",
     *    "updated_at": "2023-06-24T05:55:53.000000Z",
     *    "created_at": "2023-06-24T05:55:53.000000Z"
     *     }
     *   }
     */
    public function update(Travel $travel, UpdateTravelRequest $request)
    {

        try {
            $travel->update($request->all());

            return new GetTravelResource($travel);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Something was wrong',
                'data' => '',
            ], 422);
        }
    }
}
