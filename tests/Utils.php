<?php
namespace Tests;

class TestHelper extends TestCase{
    public static function loginMobile($this_)
    {
        $this_->seed(RoleSeeder::class);
        $this_->seed(UserSeeder::class);
        $response = $this_->post(
            uri: '/api/v1/mobile/login',
            data: [
                "email" => "admin1@email.com",
                "password" => "Admin1@12345",
                "device_name" => "vivo"
            ],
        );

        return $response;
    }

    public static function getToken($this_)
    {
        $response = TestHelper::loginMobile($this_);
        $token = $response['authorization']['token'];
        return $token;
    }
}