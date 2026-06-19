<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_public_home_page_is_available(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Milky Garage');
    }

    public function test_login_page_is_available(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login');
        $response->assertDontSee('Akun Demo');
    }
}
