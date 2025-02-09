<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_validation_error(): void
    {
        $response = $this->postJson(route('user.register'), [
            'name' => null,
            'phone_number' => null,
            'email' => 'mekhdiars$gmail.com',
            'address' => 'Епишина 33',
            'password' => 'asdfasdf',
            'password_confirmation' => 'asd',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'phone_number', 'email', 'address', 'password']);
    }

    public function test_register_success(): void
    {
        $registerData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->regexify('\+7\(\d{3}\)\d{3}\d{2}\d{2}'),
            'address' => fake()->streetName() . ', ' . fake()->buildingNumber(),
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('user.register'), $registerData);

        $response->assertCreated();

        $id = $response['id'];
        $this->assertDatabaseHas(User::class, [
            'id' => $id,
            'name' => $registerData['name'],
            'email' => $registerData['email'],
            'phone_number' => $registerData['phone_number'],
        ]);
        $this->assertTrue(
            Hash::check($registerData['password'], User::query()->find($id)->password)
        );
    }
}
