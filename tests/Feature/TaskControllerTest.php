<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function user_can_view_their_tasks()
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create([
            'title' => 'Test Task',
            'description' => 'Test Description'
        ]);

        $this->actingAs($user);

        $response = $this->get('/api/tasks'); 
        $response->assertOk();
        $response->assertSee('Test Task');
    }

    /** @test */
    public function user_can_create_a_task()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/api/tasks', [
            'title' => 'Created Task',
            'description' => 'Created Description',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('tasks', [
            'title' => 'Created Task',
            'description' => 'Created Description',
        ]);
    }

    /** @test */
    public function user_can_update_their_task()
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create([
            'title' => 'Original Title',
            'description' => 'Original Description',
        ]);

        $this->actingAs($user);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Title',
            'is_done' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Updated Title']);
        $this->assertDatabaseHas('tasks', ['title' => 'Updated Title', 'is_done' => true]);
    }

    /** @test */
    public function user_can_delete_their_task()
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create([
            'title' => 'Task to Delete',
            'description' => 'Will be gone',
        ]);

        $this->actingAs($user);

        $response = $this->delete("/api/tasks/{$task->id}");
        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}

