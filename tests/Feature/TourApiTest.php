<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TourApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_tour_can_return_travel_slug_correctecly(): void
    {
        $travel = Travel::factory()->create(['is_public'=>true]);
        $tour = Tour::factory()->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);

        $response->assertJsonCount(1,'data');

        $response->assertJsonFragment(['id' => $tour->id]);
    }

    public function test_tour_can_return_travel_for_public_recort_correctecly(): void
    {
        $travel = Travel::factory()->create(['is_public'=>true]);
        Travel::factory()->create(['is_public'=>false]);
        $tour = Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);

        $response->assertJsonCount(15,'data');

        $response->assertJsonPath('data.0.name' , $tour[0]->name);

        $response->assertJsonPath('meta.last_page' , 2);
    }
}
