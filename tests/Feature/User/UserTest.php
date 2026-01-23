<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Helpers\UserHelpers;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_return_of_all_users_successfully(): void
    {
        $tokenAdmin = UserHelpers::createTestAdminUserAuthenticated();

        foreach (range(1, 10) as $number) {
            UserHelpers::createTestUser();
        }

        $response = $this->getJson(
            'api/users',
            ['Authorization' => "Bearer {$tokenAdmin}"]
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

    public function test_should_must_return_tasks_page_2(): void
    {
        $tokenAdmin = UserHelpers::createTestAdminUserAuthenticated();
        foreach (range(1, 29) as $number) {
            UserHelpers::createTestUser();
        }

        $response = $this->getJson(
            'api/users?page=2',
            ['Authorization' => "Bearer {$tokenAdmin}"]
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
        $this->assertCount(15, $responseData);

        $this->assertEquals(2, $responseMeta['last_page']);
        $this->assertEquals(30, $responseMeta['total']);
        $this->assertEquals(15, $responseMeta['per_page']);
    }

    public function test_should_must_return_per_page_5_tasks(): void
    {
        $tokenAdmin = UserHelpers::createTestAdminUserAuthenticated();
        foreach (range(1, 10) as $number) {
            UserHelpers::createTestUser();
        }

        $response = $this->getJson(
            'api/users?per_page=5',
            ['Authorization' => "Bearer {$tokenAdmin}"]
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

        $this->assertEquals(3, $responseMeta['last_page']);
        $this->assertEquals(11, $responseMeta['total']);
        $this->assertEquals(5, $responseMeta['per_page']);
    }

    public function test_should_of_returning_a_user_successfully(): void
    {
        $tokenAdmin = UserHelpers::createTestAdminUserAuthenticated();

        $user = UserHelpers::createTestUser();
        $response = $this->getJson(
            "api/users/{$user->id}",
            ['Authorization' => "Bearer {$tokenAdmin}"]
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

    public function test_should_of_returning_a_user_non_existent(): void
    {
        $tokenAdmin = UserHelpers::createTestAdminUserAuthenticated();
        $response = $this->getJson(
            'api/users/00000000-0000-0000-0000-000000000000',
            ['Authorization' => "Bearer {$tokenAdmin}"]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_should_create_of_a_new_task_successfully(): void
    {
        $tokenAdmin = UserHelpers::createTestAdminUserAuthenticated();
        $user = UserHelpers::createFakeTestUser();

        $params = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(
            'api/users',
            $params,
            ['Authorization' => "Bearer {$tokenAdmin}"]
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

    public function test_should_create_of_a_new_user_with_missing_data(): void
    {
        $tokenAdmin = UserHelpers::createTestAdminUserAuthenticated();
        $user = UserHelpers::createFakeTestUser();
        $params = [
            'name' => $user->name,
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson(
            'api/users',
            $params,
            ['Authorization' => "Bearer {$tokenAdmin}"]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_create_of_a_new_user_with_invalid_email(): void
    {
        $tokenAdmin = UserHelpers::createTestAdminUserAuthenticated();
        $user = UserHelpers::createFakeTestUser();
        $params = [
            'name' => $user->name,
            'email' => 'teste',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson(
            'api/users',
            $params,
            ['Authorization' => "Bearer {$tokenAdmin}"]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_update_user_successfully(): void
    {
        $tokenAdmin = UserHelpers::createTestAdminUserAuthenticated();
        $user = UserHelpers::createTestUser();
        $userUpdate = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
        ];
        $response = $this->putJson(
            "api/users/{$user->id}",
            $userUpdate,
            ['Authorization' => "Bearer {$tokenAdmin}"]
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

    public function test_should_delete_user_successfully(): void
    {
        $tokenAdmin = UserHelpers::createTestAdminUserAuthenticated();
        $user = UserHelpers::createTestUser();
        $response = $this->deleteJson(
            "api/users/{$user->id}",
            [],
            ['Authorization' => "Bearer {$tokenAdmin}"]
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
