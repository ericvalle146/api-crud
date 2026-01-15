<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Feature\Helpers\TaskHelpers;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_return_of_all_tasks()
    {
        foreach (range(1, 10) as $number) {
            TaskHelpers::createTestTask();
        }

        $response = $this->getJson(
            '/api/tasks'
        );

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([

            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'due_date',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

    }

    public function test_of_returning_a_task()
    {
        $task = TaskHelpers::createTestTask();
        $response = $this->getJson(
            "api/tasks/{$task->id}"
        );

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'status',
                'due_date',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_creation_of_a_new_task()
    {
        $task = TaskHelpers::createFakeTestTask();
        $params = [
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status->value,
            'due_date' => $task->due_date?->format('Y-m-d'),
        ];

        $response = $this->postJson(
            '/api/tasks',
            $params
        );
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'due_date',
                ],
            ]);
    }

    public function test_to_update_task()
    {
        $task = TaskHelpers::createTestTask();
        $taskToUpdate = TaskHelpers::createFakeTestTask()->toArray();

        $response = $this->putJson(
            "api/tasks/{$task->id}",
            $taskToUpdate
        );

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([

            'data' => [
                'title',
                'description',
                'status',
                'due_date',
                'created_at',
                'updated_at',

            ],
        ]);
    }

    public function test_to_delete_task()
    {
        $task = TaskHelpers::createTestTask();
        $response = $this->delete(
            "api/tasks/{$task->id}"
        );
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
