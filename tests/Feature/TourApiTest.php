<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_tour_can_return_travel_slug_correctecly(): void
    {
        $travel = Travel::factory()->create(['is_public' => true]);
        $tour = Tour::factory()->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);

        $response->assertJsonCount(1, 'data');

        $response->assertJsonFragment(['id' => $tour->id]);
    }

    public function test_tour_can_return_travel_for_public_recort_correctecly(): void
    {
        $travel = Travel::factory()->create(['is_public' => true]);
        Travel::factory()->create(['is_public' => false]);
        $tour = Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);

        $response->assertJsonCount(15, 'data');

        $response->assertJsonPath('data.0.name', $tour[0]->name);

        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_filter_search_by_priceFrom_and_priceTo(): void
    {

        $travel = Travel::factory()->create(['is_public' => true]);
        Travel::factory()->create(['is_public' => false]);
        Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours'.'?priceTo=100&&priceFrom=300&priceTo=800');

        dd($response);
        $response->assertStatus(200);

        $data = $response->json();
        $currentprice = $data['data'][0]['price'];

        $this->assertGreaterThan(300, $currentprice); // Assert that the current price is greater than 800
        $this->assertLessThan(800, $currentprice); // A

        $currentprice = $data['data'][count($data)]['price'];

        $this->assertGreaterThan(300, $currentprice); // Assert that the current price is greater than 800
        $this->assertLessThan(800, $currentprice); // A

    }

    public function test_filter_search_by_dateFrom_and_dateTo(): void
    {

        $travel = Travel::factory()->create(['is_public' => true]);
        Travel::factory()->create(['is_public' => false]);
        Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours'.'?dateTo=2023-06-21&&dateFrom=2023-06-11');

        $response->assertStatus(200);

        $data = $response->json();
        $start_date = $data['data'][0]['start_date'];
        $end_date = $data['data'][0]['end_date'];

        $this->assertTrue(Carbon::parse($start_date)->greaterThan(Carbon::parse('2023-06-11')));
        $this->assertTrue(Carbon::parse($end_date)->greaterThanOrEqualTo(Carbon::parse('2023-06-22')));
    }

    public function test_filter_search_by_orderBy_and_sortBy(): void
    {

        $travel = Travel::factory()->create(['is_public' => true]);
        Travel::factory()->create(['is_public' => false]);
        Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours'.'?orderBy=price&sortBy=asc');

        $response->assertStatus(200);

        $responseData = $response->json();
        $prices = collect($responseData['data'])->pluck('price');
        $sortedPrices = $prices->sort();

        $this->assertEquals($sortedPrices, $prices);
    }
}
