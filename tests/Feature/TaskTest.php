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

    public function test_must_return_tasks_page_2()
    {
        foreach (range(1, 28) as $number) {
            TaskHelpers::createTestTask();
        }
        $response = $this->getJson(
            'api/tasks?page=2'
        );

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['data', 'links', 'meta']);

        $responseData = $response->json('data');

        $responseMeta = $response->json('meta');

        $this->assertIsArray($responseData);

        $this->assertCount(13, $responseData);

        $this->assertEquals(2, $responseMeta['last_page']);

        $this->assertEquals(15, $responseMeta['per_page']);

        $this->assertEquals(28, $responseMeta['total']);

    }

    public function test_should_return_5_tasks_per_page()
    {
        foreach (range(1, 15) as $number) {
            TaskHelpers::createTestTask();
        }

        $response = $this->getJson(
            'api/tasks?per_page=5'
        );

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['data', 'links', 'meta']);

        $responseData = $response->json('data');

        $responseMeta = $response->json('meta');

        $this->assertIsArray($responseData);

        $this->assertCount(5, $responseData);

        $this->assertEquals(5, $responseMeta['per_page']);

        $this->assertEquals(15, $responseMeta['total']);

        $this->assertEquals(3, $responseMeta['last_page']);
    }

    public function test_of_returning_a_task()
    {
        $task = TaskHelpers::createTestTask();
        $response = $this->getJson(
            "api/tasks/{$task->id}"
        );

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'id',
            'title',
            'description',
            'status',
            'due_date',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_of_returning_a_task_non_existent()
    {
        $response = $this->getJson(
            'api/tasks/00000000-0000-0000-0000-000000000000'
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);

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
                'id',
                'title',
                'description',
                'status',
                'due_date',
            ]);
    }

    public function test_creation_of_a_new_task_with_missing_data()
    {
        $task = TaskHelpers::createFakeTestTask();
        $params = [
            'title' => '',
            'description' => $task->description,
            'status' => $task->status->value,
            'due_date' => $task->due_date?->format('Y-m-d'),
        ];

        $response = $this->postJson(
            '/api/tasks',
            $params
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function test_creation_of_a_new_task_with_invalid_title()
    {
        $task = TaskHelpers::createFakeTestTask();
        $params = [
            'title' => 'xx',
            'description' => $task->description,
            'status' => $task->status->value,
            'due_date' => $task->due_date?->format('Y-m-d'),
        ];

        $response = $this->postJson(
            '/api/tasks',
            $params
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

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

            'title',
            'description',
            'status',
            'due_date',
            'created_at',
            'updated_at',

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
