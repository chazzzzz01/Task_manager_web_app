<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
public function authenticated_user_can_create_task_through_web()
{
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/tasks', [
        'title' => 'Test Task',
        'description' => 'Created via test',
        '_token' => csrf_token(), 
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseHas('tasks', [
        'title' => 'Test Task',
        'description' => 'Created via test',
    ]);
}
}
