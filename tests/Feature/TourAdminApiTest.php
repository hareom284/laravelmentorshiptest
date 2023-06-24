<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourAdminApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_auth_cannot_create_tours(): void
    {

        $travel = Travel::factory(1)->create()->first();

        $response = $this->postJson('/api/v1/admin/travels/'.$travel->id.'/tour', [
            'name' => 'road to bagon',
            'start_date' => '2023-06-19',
            'end_date' => '2023-06-29',
            'price' => '999',
        ]);

        $response->assertStatus(401);
    }

    /***
     *  post travel data assign admin role
     *  @return 200
     */
    public function test_create_tour_by_admin_roles(): void
    {
        $this->seed();

        $user = User::factory()->create();

        $role_id = Role::where('name', config('userrole.admin'))->pluck('id')->first();
        $user->roles()->sync([$role_id]);

        $this->actingAs($user);

        $travel = Travel::factory(1)->create()->first();

        $response = $this->postJson('/api/v1/admin/travels/'.$travel->id.'/tour', [
            'name' => 'road to bagon',
            'start_date' => '2023-06-19',
            'end_date' => '2023-06-29',
            'price' => '99',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.name', 'road to bagon');
        $response->assertJsonPath('data.travel_id', $travel->id);
        $response->assertJsonPath('data.start_date', '2023-06-19');
        $response->assertJsonPath('data.end_date', '2023-06-29');
        $response->assertJsonPath('data.price', '99.00');
    }

    public function test_cannot_create_tour_by_editor_role(): void
    {
        $this->seed();

        $user = User::factory()->create();

        $role_id = Role::where('name', config('userrole.editor'))->pluck('id')->first();
        $user->roles()->sync([$role_id]);

        $this->actingAs($user);

        $travel = Travel::factory(1)->create()->first();

        $response = $this->postJson('/api/v1/admin/travels/'.$travel->id.'/tour', [
            'name' => 'road to bagon',
            'start_date' => '2023-06-19',
            'end_date' => '2023-06-29',
            'price' => '9.99',
        ]);

        $response->assertStatus(403);
    }
}
