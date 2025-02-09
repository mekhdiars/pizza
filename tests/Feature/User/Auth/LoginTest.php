<?php

namespace Tests\Feature\User\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_validation_error(): void
    {
        $response = $this->postJson(route('user.login'), [
            'login' => 'asdfasdf',
            'password' => 'asdfasdf',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['login']);
    }

    public function test_login_with_email(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('user.login'), [
            'login' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['token']);
    }

    public function test_login_with_phone(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('user.login'), [
            'login' => $user->phone_number,
            'password' => 'password',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['token']);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('user.login'), [
            'login' => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['message']);
    }
}
