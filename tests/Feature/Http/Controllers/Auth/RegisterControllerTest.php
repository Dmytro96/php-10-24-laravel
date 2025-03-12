<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\SetupTrait;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
//    use RefreshDatabase;
    
    public function test_successful_registration(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'lastname' => 'User',
            'email' => 'testuser@example.com',
            'phone' => '1234567890',
            'birthdate' => '2000-01-01',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }
}
