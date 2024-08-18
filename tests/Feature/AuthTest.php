<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    private function login()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $response = $this->post(
            uri: '/api/v1/login',
            data: [
                'email' => 'admin1@email.com',
                'password' => 'Admin1@12345'
            ],
        );

        return $response;
    }

    private function loginMobile()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $response = $this->post(
            uri: '/api/v1/mobile/login',
            data: [
                'email' => 'admin1@email.com',
                'password' => 'Admin1@12345',
                'device_name' => 'vivo'
            ],
        );

        return $response;
    }

    private function getToken()
    {
        $response = $this->loginMobile();
        $token = $response['authorization']['token'];
        return $token;
    }

    /**
     * @group auth-login
     */
    public function test_login_success(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $response = $this->post(
            uri: '/api/v1/login',
            data: [
                'email' => 'admin1@email.com',
                'password' => 'Admin1@12345'
            ],
        );
        $response->assertStatus(200);
        $response
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->where('success', true)
                    ->has('user', fn($json) =>
                        $json
                            ->where('name', 'admin1')
                            ->where('email', 'admin1@email.com')
                    )
            );

    }

    /**
     * @group auth-login
     */
    public function test_login_failed_unauthorized(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $response = $this->post(
            uri: '/api/v1/login',
            data: [
                'email' => 'admin2@email.com',
                'password' => 'Admin2@12345'
            ],
        );
        $response->assertStatus(401);
        $response->assertJson([
            'errors' => [
                'message' => 'User email and password not valid'
            ]
        ]);
    }

    /**
     * @group auth-login
     */
    public function test_login_failed_bad_request(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $response = $this->post(
            uri: '/api/v1/login',
            data: [
                'password' => 'Addmin'
            ],
        );
        $response->assertStatus(400);
        $response->assertJson([
            'errors' => [
                'email' => [ 'The email field is required.' ],
                'password' => [ 'The password field must be at least 8 characters.']
            ]
        ]);
    }

    /**
     * @group auth-register
     */
    public function test_register_bad_request(): void
    {
        $this->seed(RoleSeeder::class);
        // $this->seed(UserSeeder::class);
        $response = $this->post(
            uri: '/api/v1/register',
            data: [
                'email' => 'admin3@email.com',
                'password' => 'admin'
            ],
        );
        $response->assertStatus(400);
        $response->assertJsonValidationErrors([
            'name', 'password'
        ]);
    }

    /**
     * @group auth-register
     */
    public function test_register_success(): void
    {
        $this->seed(RoleSeeder::class);
        // $this->seed(UserSeeder::class);
        $response = $this->post(
            uri: '/api/v1/register',
            data: [
                'name' => 'admin3',
                'email' => 'admin3@email.com',
                'password' => 'Admin3@12345'
            ],
        );
        $response->assertStatus(201);
        $response->assertJson(
                    fn (AssertableJson $json) => 
                    $json->has('success')
                        ->has('message')
                        ->has('user',fn ($json) =>
                            $json->has('name')
                                ->has('email')
                                ->has('updated_at')
                                ->has('created_at')
                                ->has('id')
                        )
                );
        $response->assertJson([
            'success' => true,
            'message' => 'User created successfully',
            'user' => [
                'name' => 'admin3',
                'email' => 'admin3@email.com'
            ]
        ]);
    }
    
    /**
     * @group auth-logout
     */
    public function test_logout_success(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->plainTextToken;
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->post(
            uri: '/api/v1/logout'
        );
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * @group auth-logout
     */
    public function test_logout_fail(): void
    {
        $response = $this->post(
            uri: '/api/v1/logout'
        );
        $response->assertStatus(401);
        $response->assertJson([
            'errors' => [
                'message' => 'UnAuthenticated'
            ]
        ]);
    }

    /**
     * @group auth-refresh
     */
    public function test_refresh_token_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->post(
            uri: 'api/v1/mobile/refresh-token',
            data: [
                'device_name' => 'vivo'
            ]
        );

        $response->assertJson(
            fn (AssertableJson $json) => 
            $json->where('success', true)  
                ->has('user', fn($json) =>
                    $json->has('name')
                        ->has('email')
                )
                ->has('authorization', fn($json) =>
                    $json->has('token')
                        ->where('type', 'Bearer')
                )
        );
    }

    /**
     * @group auth-refresh
     */
    public function test_refresh_token_validation_error(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->post(
            uri: 'api/v1/mobile/refresh-token'
        );

        $response->assertJson([
            'errors' => [
                'message' => 'The device name field is required.'
            ]
        ]);
    }

    /**
     * @group auth-register-admin
     */
    public function test_auth_admin_register_success(): void
    {
        $token = $this->getToken();
        $response = $this->post(
            uri: 'api/v1/admin/register',
            data: [
                'name' => 'admin2',
                'email' => 'admin2@email.com',
                'password' => 'Admin2@123'
            ],
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(201);

        $response->assertJson([ 
            'success' => true,
            'message' => 'User created successfully',
            'user' => [
                'name' => 'admin2',
                'email' => 'admin2@email.com'
            ]
        ]);
    }

     /**
     * @group auth-register-admin
     */
    public function test_auth_admin_register_validation_error(): void
    {
        $token = $this->getToken();
        $response = $this->post(
            uri: 'api/v1/admin/register',
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(400);

        $response->assertJsonValidationErrors(
            ['name', 'email', 'password']
        );
    }

    /**
     * @group auth-register-admin
     */
    public function test_auth_admin_register_unauthorize(): void
    {
        $token = $this->getToken();
        $response = $this->post(
            uri: 'api/v1/admin/register',
            data: [
                'name' => 'admin2',
                'email' => 'admin2@email.com',
                'password' => 'Admin2@123'
            ],
        );

        $response->assertStatus(401);

        $response->assertJson([
            'errors' => [
                'message' => 'UnAuthenticated'
            ]
        ]);
    }
}
