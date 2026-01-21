<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Helpers\UserHelpers;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_return_of_all_users_successfully()
    {
        foreach (range(1, 10) as $number) {
            UserHelpers::createTestUser();
        }

        $response = $this->getJson(
            'api/auth/users'
        );
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_should_must_return_tasks_page_2()
    {
        foreach (range(1, 28) as $number) {
            UserHelpers::createTestUser();
        }

        $response = $this->getJson(
            'api/auth/users?page=2'
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $responseData = $response->json('data');
        $responseMeta = $response->json('meta');

        $this->assertIsArray($responseData);
        $this->assertCount(13, $responseData);

        $this->assertEquals(2, $responseMeta['last_page']);
        $this->assertEquals(28, $responseMeta['total']);
        $this->assertEquals(15, $responseMeta['per_page']);
    }

    public function test_should_must_return_per_page_5_tasks()
    {
        foreach (range(1, 10) as $number) {
            UserHelpers::createTestUser();
        }

        $response = $this->getJson(
            'api/auth/users?per_page=5'
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
        $responseData = $response->json('data');
        $responseMeta = $response->json('meta');

        $this->assertIsArray($responseData);
        $this->assertCount(5, $responseData);

        $this->assertEquals(2, $responseMeta['last_page']);
        $this->assertEquals(10, $responseMeta['total']);
        $this->assertEquals(5, $responseMeta['per_page']);
    }

    public function test_should_of_returning_a_user_successfully()
    {
        $user = UserHelpers::createTestUser();
        $response = $this->getJson(
            "api/auth/users/{$user->id}"
        );
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_should_of_returning_a_user_non_existent()
    {
        $response = $this->getJson(
            'api/auth/users/00000000-0000-0000-0000-000000000000'
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);

    }

    public function test_should_create_of_a_new_task_successfully()
    {
        $user = UserHelpers::createFakeTestUser();

        $params = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(
            'api/auth/users',
            $params
        );

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_should_create_of_a_new_user_with_missing_data()
    {
        $user = UserHelpers::createFakeTestUser();
        $params = [
            'name' => $user->name,
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson(
            'api/auth/users',
            $params
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_create_of_a_new_user_with_invalid_email()
    {
        $user = UserHelpers::createFakeTestUser();
        $params = [
            'name' => $user->name,
            'email' => 'teste',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson(
            'api/auth/users',
            $params
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_update_user_successfully()
    {
        $user = UserHelpers::createTestUser();
        $userUpdate = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
        ];
        $response = $this->putJson(
            "api/auth/users/{$user->id}",
            $userUpdate
        );
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_should_delete_user_successfully()
    {
        $user = UserHelpers::createTestUser();
        $response = $this->deleteJson(
            "api/auth/users/{$user->id}"
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
