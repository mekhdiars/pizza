<?php

namespace Feature\Admin\Auth;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AccessToAdminRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_no_access(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('admin.products.index'));

        $response->assertForbidden();
    }

    public function test_admin_access(): void
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson(route('admin.products.index'));

        $response->assertOk();
    }
}
