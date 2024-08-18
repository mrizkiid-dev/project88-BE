<?php

namespace Tests\Feature;

use App\Enums\SortEnum;
use App\Enums\StatusOrderEnum;
use App\Models\Order;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class OrderTest extends TestCase
{
    private function login()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $response = $this->post(
            uri: '/api/v1/login',
            data: [
                "email" => "admin1@email.com",
                "password" => "Admin1@12345"
            ],
        );

        return $response;
    }

    private function loginMobile()
    {
        // $this->seed(RoleSeeder::class);
        // $this->seed(UserSeeder::class);
        $response = $this->post(
            uri: '/api/v1/mobile/login',
            data: [
                "email" => "admin1@email.com",
                "password" => "Admin1@12345",
                "device_name" => "vivo"
            ],
        );

        return $response;
    }

    private function loginMobileUser()
    {
        // $this->seed(RoleSeeder::class);
        // $this->seed(UserSeeder::class);
        $response = $this->post(
            uri: '/api/v1/mobile/login',
            data: [
                "email" => "user1@email.com",
                "password" => "User1@12345",
                "device_name" => "vivo"
            ],
        );

        return $response;
    }

    private function getToken($isAdmin)
    {
        $this->seed(DatabaseSeeder::class);
        if ($isAdmin) {
            $response = $this->loginMobile();
        } else {
            $response = $this->loginMobileUser();
        }
        $token = $response['authorization']['token'];
        return $token;
    }

    /**
     * @group order-get
     */
    public function test_order_get_success()
    {
        $token = $this->getToken(true);
        $response = $this->get(
            uri: 'api/v1/admin/orders',
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'meta' => [
                    'current_page',
                    'first_item',
                    'last_page',
                    'per_page',
                    'last_item',
                    'total'
                ],
                'data' => [
                    '*' => [
                        'id',
                        'shopping_session_id',
                        'name_receiver',
                        'detail_address',
                        'province' => [
                            'id', 'name'
                        ],
                        'city' => [
                            'id', 'name'
                        ],
                        'shipping' => [
                            'provider', 'price'
                        ],
                        'total_payment',
                        'midtrans' => [
                            'id', 'token'
                        ],
                        'status_order',
                        'created_at',
                        'modified_at'
                    ]
                ]
            ]);
    }

    /**
     * @group order-get-filter
     */
    public function test_order_get_success_filter_name()
    {
        $token = $this->getToken(true);
        $response = $this->get(
            uri: 'api/v1/admin/orders?name-receiver='.'2',
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'meta' => [
                    'current_page',
                    'first_item',
                    'last_page',
                    'per_page',
                    'last_item',
                    'total'
                ],
                'data' => [
                    '*' => [
                        'id',
                        'shopping_session_id',
                        'name_receiver',
                        'detail_address',
                        'province' => [
                            'id', 'name'
                        ],
                        'city' => [
                            'id', 'name'
                        ],
                        'shipping' => [
                            'provider', 'price'
                        ],
                        'total_payment',
                        'midtrans' => [
                            'id', 'token'
                        ],
                        'status_order',
                        'created_at',
                        'modified_at'
                    ]
                ]
            ])
        ->assertJsonFragment([
            'total' => 1,
            'name_receiver' => 'user2'
        ]);
    }

    /**
     * @group order-get-filter
     */
    public function test_order_get_success_filter_order_by_descending_and_page_size()
    {
        $token = $this->getToken(true);
        $response = $this->get(
            uri: 'api/v1/admin/orders?sort-by-date=desc&page=2&size=2',
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'meta' => [
                    'current_page',
                    'first_item',
                    'last_page',
                    'per_page',
                    'last_item',
                    'total'
                ],
                'data' => [
                    '*' => [
                        'id',
                        'shopping_session_id',
                        'name_receiver',
                        'detail_address',
                        'province' => [
                            'id', 'name'
                        ],
                        'city' => [
                            'id', 'name'
                        ],
                        'shipping' => [
                            'provider', 'price'
                        ],
                        'total_payment',
                        'midtrans' => [
                            'id', 'token'
                        ],
                        'status_order',
                        'created_at',
                        'modified_at'
                    ]
                ]
            ])
        ->assertJsonFragment([
            'total' => 4,
            'current_page' => 2,
            'per_page' => 2
        ]);
    }

    /**
     * @group order-get
     */
    public function test_order_get_403()
    {
        $token = $this->getToken(false);
        $response = $this->get(
            uri: 'api/v1/admin/orders',
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(403)
            ->assertJson([
                'errors' => [
                    'message' => 'Forbidden, you do not have access'
                ]
            ]);
    }

    /**
     * @group order-get
     */
    public function test_order_get_fail_401()
    {
        $token = $this->getToken(false);
        $response = $this->get(
            uri: 'api/v1/admin/orders',
        );

        $response->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => "UnAuthenticated"
                ]
            ]);
    }

    /**
     * @group order-get-id
     */
    public function test_order_id_get_success()
    {
        $token = $this->getToken(true);
        $order = Order::limit(1)->get();
        $response = $this->get(
            uri: 'api/v1/admin/orders/'.$order[0]->id,
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'shopping_session_id',
                'name_receiver',
                'detail_address',
                'province' => [
                    'id', 'name'
                ],
                'city' => [
                    'id', 'name'
                ],
                'shipping' => [
                    'provider', 'price'
                ],
                'total_payment',
                'midtrans' => [
                    'id', 'token'
                ],
                'status_order',
                'created_at',
                'modified_at'
            ]);
        ;
    }

    /**
     * @group order-get-id
     */
    public function test_order_get_id_get_403()
    {
        $token = $this->getToken(false);
        $order = Order::limit(1)->get();
        $response = $this->get(
            uri: 'api/v1/admin/orders/'.$order[0]->id,
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(403)
            ->assertJson([
                "errors" => [
                    'message' => 'Forbidden, you do not have access'
                ]
            ])
        ;
    }

    /**
     * @group order-get-id
     */
    public function test_order_get_id_get_401()
    {
        $token = $this->getToken(false);
        $order = Order::limit(1)->get();
        $response = $this->get(
            uri: 'api/v1/admin/orders/'.$order[0]->id
        );

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => 'UnAuthenticated',
                ]
            ])
        ;
    }

    /**
     * @group order-update
     */
    public function test_order_update_success()
    {
        $token = $this->getToken(true);
        $order = Order::limit(1)->get();
        $status_order = StatusOrderEnum::NEED_CONFIRMATION->value;
        $response = $this->patch(
            uri: 'api/v1/admin/orders/'.$order[0]->id.'/status',
            data: [
                "status_order" => [
                    "status" => $status_order
                ]
            ],
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'shopping_session_id',
                    'name_receiver',
                    'detail_address',
                    'province' => [
                        'id', 'name'
                    ],
                    'city' => [
                        'id', 'name'
                    ],
                    'shipping' => [
                        'provider', 'price'
                    ],
                    'total_payment',
                    'midtrans' => [
                        'id', 'token'
                    ],
                    'status_order',
                    'created_at',
                    'modified_at'
                ]
            ])
            ->assertJson(
                function (AssertableJson $json) use ($status_order) {
                    $json->has('data',function($json) use ($status_order){
                        $json->where('status_order', $status_order)->etc();
                    })->etc();
                }
            );
    }

    /**
     * @group order-update
     */
    public function test_order_update_401()
    {
        $token = $this->getToken(true);
        $order = Order::limit(1)->get();
        $status_order = StatusOrderEnum::NEED_CONFIRMATION->value;
        $response = $this->patch(
            uri: 'api/v1/admin/orders/'.$order[0]->id.'/status',
            data: [
                "status_order" => [
                    "status" => $status_order
                ]
            ],
        );

        $response->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => "UnAuthenticated"
                ]
            ]);
    }

    /**
     * @group order-update
     */
    public function test_order_update_403()
    {
        $token = $this->getToken(false);
        $order = Order::limit(1)->get();
        $status_order = StatusOrderEnum::NEED_CONFIRMATION->value;
        $response = $this->patch(
            uri: 'api/v1/admin/orders/'.$order[0]->id.'/status',
            data: [
                "status_order" => [
                    "status" => $status_order
                ]
            ],
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(403)
            ->assertJson([
                'errors' => [
                    'message' => 'Forbidden, you do not have access'
                ]
            ]);
    }

    /**
     * @group order-update
     */
    public function test_order_update_400_validation_error()
    {
        $token = $this->getToken(true);
        $order = Order::limit(1)->get();
        $status_order = StatusOrderEnum::NEED_CONFIRMATION->value;
        $response = $this->patch(
            uri: 'api/v1/admin/orders/'.$order[0]->id.'/status',
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['status_order', 'status_order.status']);
    }
}
