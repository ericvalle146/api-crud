<?php

declare(strict_types=1);

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_should_return_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
