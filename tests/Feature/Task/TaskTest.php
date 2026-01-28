<?php

declare(strict_types=1);

namespace Tests\Feature\Task;

use App\Actions\User\FetchUser;
use App\Enums\Task\TaskStatus;
use App\Events\Task\TaskCompleted;
use App\Jobs\Task\SendTaskCompletedEmailJob;
use App\Listeners\Task\SendTaskCompletedNotification;
use App\Mail\Task\TaskCompletedMail;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\Feature\Helpers\TaskHelpers;
use Tests\Feature\Helpers\UserHelpers;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_return_of_all_tasks_successfully(): void
    {
        foreach (range(1, 10) as $number) {
            TaskHelpers::createTestTask();
        }
        $dataUser = UserHelpers::userIdAndToken();

        $response = $this->getJson(
            '/api/tasks',
            ['Authorization' => 'bearer ' . $dataUser[1]]
        );

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
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

    public function test_should_must_return_tasks_page_2(): void
    {
        foreach (range(1, 28) as $number) {
            TaskHelpers::createTestTask();
        }
        $dataUser = UserHelpers::userIdAndToken();

        $response = $this->getJson(
            'api/tasks?page=2',
            ['Authorization' => 'bearer ' . $dataUser[1]]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data', 'links', 'meta']);

        $responseData = $response->json('data');
        $responseMeta = $response->json('meta');

        $this->assertIsArray($responseData);
        $this->assertCount(13, $responseData);
        $this->assertEquals(2, $responseMeta['last_page']);
        $this->assertEquals(15, $responseMeta['per_page']);
        $this->assertEquals(28, $responseMeta['total']);
    }

    public function test_should_return_5_tasks_per_page(): void
    {
        foreach (range(1, 15) as $number) {
            TaskHelpers::createTestTask();
        }
        $dataUser = UserHelpers::userIdAndToken();

        $response = $this->getJson(
            'api/tasks?per_page=5',
            ['Authorization' => 'bearer ' . $dataUser[1]]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data', 'links', 'meta']);

        $responseData = $response->json('data');
        $responseMeta = $response->json('meta');

        $this->assertIsArray($responseData);
        $this->assertCount(5, $responseData);
        $this->assertEquals(5, $responseMeta['per_page']);
        $this->assertEquals(15, $responseMeta['total']);
        $this->assertEquals(3, $responseMeta['last_page']);
    }

    public function test_should_of_returning_a_task_successfully(): void
    {
        $task = TaskHelpers::createTestTask();
        $dataUser = UserHelpers::userIdAndToken();

        $response = $this->getJson(
            "api/tasks/{$task->id}",
            ['Authorization' => 'bearer ' . $dataUser[1]]
        );

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'id',
            'user_id',
            'title',
            'description',
            'status',
            'due_date',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_should_of_returning_a_task_non_existent(): void
    {
        $dataUser = UserHelpers::userIdAndToken();

        $response = $this->getJson(
            'api/tasks/00000000-0000-0000-0000-000000000000',
            ['Authorization' => 'bearer ' . $dataUser[1]]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_should_create_of_a_new_task_successfully(): void
    {

        $task = TaskHelpers::createFakeTestTask();
        $dataUser = UserHelpers::userIdAndToken();

        $params = [
            'user_id' => $dataUser[0],
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status->value,
            'due_date' => $task->due_date?->format('Y-m-d'),
        ];

        $response = $this->postJson(
            '/api/tasks',
            $params,
            ['Authorization' => 'bearer ' . $dataUser[1]]
        );

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'user_id',
                'title',
                'description',
                'status',
                'due_date',
            ]);
    }

    public function test_should_create_of_a_new_task_with_missing_data(): void
    {
        $task = TaskHelpers::createFakeTestTask();
        $dataUser = UserHelpers::userIdAndToken();

        $params = [
            'user_id' => $dataUser[0],
            'title' => '',
            'description' => $task->description,
            'status' => $task->status->value,
            'due_date' => $task->due_date?->format('Y-m-d'),
        ];

        $response = $this->postJson(
            '/api/tasks',
            $params,
            ['Authorization' => 'bearer ' . $dataUser[1]]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_creation_of_a_new_task_with_invalid_title(): void
    {
        $task = TaskHelpers::createFakeTestTask();
        $dataUser = UserHelpers::userIdAndToken();

        $params = [
            'user_id' => $dataUser[0],
            'title' => 'xx',
            'description' => $task->description,
            'status' => $task->status->value,
            'due_date' => $task->due_date?->format('Y-m-d'),
        ];

        $response = $this->postJson(
            '/api/tasks',
            $params,
            ['Authorization' => 'bearer ' . $dataUser[1]]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_update_task_successfully()
    {
        $task = TaskHelpers::createTestTask();
        $dataUser = UserHelpers::userIdAndToken();

        $taskToUpdate = TaskHelpers::createFakeTestTask()->toArray();

        $response = $this->putJson(
            "api/tasks/{$task->id}",
            $taskToUpdate,
            ['Authorization' => 'bearer ' . $dataUser[1]]
        );

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'user_id',
            'title',
            'description',
            'status',
            'due_date',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_should_delete_task_successfully(): void
    {
        $task = TaskHelpers::createTestTask();
        $dataUser = UserHelpers::userIdAndToken();

        $response = $this->delete(
            "api/tasks/{$task->id}",
            [],
            ['Authorization' => 'bearer ' . $dataUser[1]]
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_should_task_completed_event_is_dispatched()
    {
        Event::fake();

        $dataTask = TaskHelpers::createFakeTestTask();
        $dataUser = UserHelpers::userIdAndToken();

        $task = TaskHelpers::createTestTask()->create([
            'user_id' => $dataUser[0],
            'title' => $dataTask->title,
            'description' => $dataTask->description,
            'status' => TaskStatus::PENDING->value,
            'due_date' => $dataTask->due_date,
        ]);

        $response = $this->putJson(
            "api/tasks/{$task->id}",
            ['status' => TaskStatus::COMPLETED->value],
            ['Authorization' => 'bearer ' . $dataUser[1]]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'user_id',
                'title',
                'description',
                'status',
                'due_date',
                'created_at',
                'updated_at',
            ]);

        Event::assertDispatched(TaskCompleted::class);
    }

    public function test_should_listener_task_dispatches_job(): void
    {
        Queue::fake();
        $dataUser = UserHelpers::userIdAndToken();
        $task = Task::factory()->create([
            'user_id' => $dataUser[0],
            'title' => 'Teste',
            'description' => 'desc',
            'status' => TaskStatus::PENDING->value,
        ]);
        $listener = app(SendTaskCompletedNotification::class);
        $event = new TaskCompleted($task);
        $listener->handle($event);

        Queue::assertPushed(SendTaskCompletedEmailJob::class);
    }

    public function test_should_queue_task_completed_job()
    {
        Queue::fake();
        $dataUser = UserHelpers::userIdAndToken();
        $task = Task::factory()->create([
            'user_id' => $dataUser[0],
            'title' => 'Teste',
            'description' => 'desc',
            'status' => TaskStatus::PENDING->value,
        ]);
        SendTaskCompletedEmailJob::dispatch($task);

        Queue::assertPushed(SendTaskCompletedEmailJob::class);
    }

    public function test_should_send_task_completed_email(): void
    {
        Mail::fake();

        $dataUser = UserHelpers::userIdAndToken();
        $task = Task::factory()->create([
            'user_id' => $dataUser[0],
            'title' => 'Teste',
            'description' => 'desc',
            'status' => TaskStatus::PENDING->value,
        ]);

        $job = new SendTaskCompletedEmailJob($task);
        $job->handle(app(FetchUser::class));

        Mail::assertSent(TaskCompletedMail::class);
    }
}
