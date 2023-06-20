<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Role;
use Tests\TestCase;

class TravelAdminTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_no_auth_cannot_create_travel(): void
    {


        $response = $this->postJson('/api/v1/admin/travels', [
            'name' => 'this is demo travel',
            'description' => 'this is description tour',
            'is_public' => true,
            'number_of_days' => 20
        ]);


        $response->assertStatus(401);

    }

    /***
     *  post travel data assign admin role
     *  @return 200
     */
    public function test_create_travel_by_admin_roles():void
    {
        $this->seed();

        $user = User::factory()->create();

        $role_id = Role::where("name",config('userrole.admin'))->pluck("id")->first();
        $user->roles()->sync([$role_id]);

        $this->actingAs($user);
        $response = $this->postJson('/api/v1/admin/travels', [
            'name' => 'this is demo travel',
            'description' => 'this is description tour',
            'is_public' => true,
            'number_of_days' => 20
        ]);
        $response->assertStatus(201);
        $response->assertJsonPath("data.name","this is demo travel");
        $response->assertJsonPath("data.description","this is description tour");
        $response->assertJsonPath("data.is_public",true);
        $response->assertJsonPath("data.number_of_days",20);
    }

    public function test_cannot_create_travel_by_editor_role():void
    {
        $this->seed();

        $user = User::factory()->create();

        $role_id = Role::where("name",config('userrole.editor'))->pluck("id")->first();
        $user->roles()->sync([$role_id]);

        $this->actingAs($user);
        $response = $this->postJson('/api/v1/admin/travels', [
            'name' => 'this is demo travel',
            'description' => 'this is description tour',
            'is_public' => true,
            'number_of_days' => 20
        ]);
        $response->assertStatus(403);
    }
}
