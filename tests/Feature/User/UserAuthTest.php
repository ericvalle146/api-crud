<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Helpers\UserHelpers;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_login_with_email_successfully(): void
    {
        $user = UserHelpers::createTestAdminUser();
        $params = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson(
            'api/auth/login',
            $params,
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['data', 'token']);
    }

    public function test_should_fail_to_login_with_wrong_credentials(): void
    {
        $user = UserHelpers::createTestAdminUser();
        $params = [
            'email' => $user->email,
            'password' => 'password______',
        ];

        $response = $this->postJson(
            'api/auth/login',
            $params,
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_should_logout_successfully(): void
    {
        $adminToken = UserHelpers::createTestAdminUserAuthenticated();

        $response = $this->postJson(
            'api/auth/logout',
            [],
            ['Authorization' => "Bearer {$adminToken}"]
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_should_not_logout_with_invalid_token(): void
    {
        $response = $this->postJson(
            'api/auth/logout',
            [],
            ['Authorization' => 'Bearer XXXXXXXXXX']
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_should_return_me_successfully(): void
    {
        $adminToken = UserHelpers::createTestAdminUserAuthenticated();

        $response = $this->getJson(
            'api/auth/me',
            ['Authorization' => "Bearer {$adminToken}"]
        );

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_should_not_return_me_with_invalid_token(): void
    {
        $response = $this->getJson(
            'api/auth/me',
            ['Authorization' => 'Bearer XXXXXXXXXX']
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
