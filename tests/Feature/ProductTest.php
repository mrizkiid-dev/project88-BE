<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
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

    private function loginMobileUser()
    {
        $response = $this->post(
            uri: '/api/v1/mobile/login',
            data: [
                'email' => 'user1@email.com',
                'password' => 'User1@12345',
                'device_name' => 'vivo'
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
     * @group products-get
     */
    public function test_product_get_success()
    {
        $token = $this->getToken(true);
        $response = $this->get(
            uri: '/api/v1/admin/products',
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
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
                        'sku',
                        'name',
                        'description',
                        'category' => [
                            'id',
                            'name',
                            'description'
                        ],
                        'product_image' => [
                            '*' => [
                                'id', 'image_url'
                            ]
                        ],
                        'price',
                        'discount',
                        'qty',
                        'weight',
                        'sell_out',
                        'created_at',
                        'modified_at'
                    ]
                ]
            ]);
    }

    /**
     * @group products-get-filter
     */
    public function test_product_get_success_filter_sku()
    {
        $token = $this->getToken(true);
        $response = $this->get(
            uri: '/api/v1/admin/products?sku=-1',
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
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
                        'sku',
                        'name',
                        'description',
                        'category' => [
                            'id',
                            'name',
                            'description'
                        ],
                        'product_image' => [
                            '*' => [
                                'id', 'image_url'
                            ]
                        ],
                        'price',
                        'discount',
                        'qty',
                        'weight',
                        'sell_out',
                        'created_at',
                        'modified_at'
                    ]
                ]
            ])->assertJsonFragment([
                'total' => 11,
                'sku' => 'SKU-SEED-TEST-1',
                'sku' => 'SKU-SEED-TEST-11',
            ])->assertJsonMissing([
                'sku' => 'SKU-SEED-TEST-2',
            ]);
    }

    /**
     * @group products-get-filter
     */
    public function test_product_get_success_filter_name()
    {
        $token = $this->getToken(true);
        $response = $this->get(
            uri: '/api/v1/admin/products?name=name 1',
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
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
                        'sku',
                        'name',
                        'description',
                        'category' => [
                            'id',
                            'name',
                            'description'
                        ],
                        'product_image' => [
                            '*' => [
                                'id', 'image_url'
                            ]
                        ],
                        'price',
                        'discount',
                        'qty',
                        'weight',
                        'sell_out',
                        'created_at',
                        'modified_at'
                    ]
                ]
            ])->assertJsonFragment([
                'total' => 11,
                'name' => 'product name 10',
                'name' => 'product name 10',
            ])->assertJsonMissing([
                'name' => 'product name 2',
            ]);
    }

    /**
     * @group products-get-filter-test
     */
    public function test_product_get_success_page_size()
    {
        $token = $this->getToken(true);
        $response = $this->get(
            uri: '/api/v1/admin/products?sort-by-sellout=desc&page=3&size=2',
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
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
                        'sku',
                        'name',
                        'description',
                        'category' => [
                            'id',
                            'name',
                            'description'
                        ],
                        'product_image' => [
                            '*' => [
                                'id', 'image_url'
                            ]
                        ],
                        'price',
                        'discount',
                        'qty',
                        'weight',
                        'sell_out',
                        'created_at',
                        'modified_at'
                    ]
                ]
            ])->assertJsonFragment([
                'current_page' => 3,
                'per_page' => 2
            ]);
    }

    /**
     * @group products-get
     */
    public function test_product_get_401()
    {
        $token = $this->getToken(true);
        $response = $this->get(
            uri: '/api/v1/admin/products',
        );

        $response->assertStatus(401)
            ->assertJson(['errors' => [
                    'message' => 'UnAuthenticated'
                ]]);
    }

    /**
     * @group products-get
     */
    public function test_product_get_403()
    {
        $token = $this->getToken(false);
        $response = $this->get(
            uri: '/api/v1/admin/products',
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
     * @group products-upload
     */
    public function test_product_upload_success()
    {
        $token = $this->getToken(true);
        $categories = ProductCategory::limit(1)->get();
        $category = $categories[0];
        $payload = json_encode([
            'sku' => 'sku-test-1',
            'name' => 'name-test-1',
            'description' => 'description-test-1',
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->desc
            ],
            'price' => 10000,
            'discount' => 100,
            'qty' => 100,
            'weight' => 100
        ]);
        Storage::fake('fake-images');

        $files = [
            new UploadedFile(base_path('tests/Fixtures/test-1.webp'), 'test-1.webp', null, null, true),
            new UploadedFile(base_path('tests/Fixtures/test-2.webp'), 'test-2.webp', null, null, true),
        ];
    
        $response = $this->post(
            uri: '/api/v1/admin/products',
            data: [
                'payload' => $payload,
                'images' => $files,
            ],
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'sku',
                    'name',
                    'description',
                    'category' => [
                        'id',
                        'name',
                        'description'
                    ],
                    'product_image' => [
                        '*' => [
                            'id', 'image_url'
                        ]
                    ],
                    'price',
                    'discount',
                    'qty',
                    'weight',
                    'sell_out',
                    'created_at',
                    'modified_at'
                ]
            ]);
    }

    /**
     * @group products-upload
     */
    public function test_product_upload_400()
    {
        $token = $this->getToken(true);
        $response = $this->post(
            uri: '/api/v1/admin/products',
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(400)
            ->assertJsonValidationErrors([
                'payload', 'images'
            ]);
    }

    /**
     * @group products-upload
     */
    public function test_product_upload_401()
    {
        $token = $this->getToken(true);
        $response = $this->post(
            uri: '/api/v1/admin/products',
        );

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => 'UnAuthenticated'
                ]
            ]);
    }

    /**
     * @group products-upload
     */
    public function test_product_upload_403()
    {
        $token = $this->getToken(false);
        $categories = ProductCategory::limit(1)->get();
        $category = $categories[0];
        $payload = json_encode([
            'sku' => 'sku-test-1',
            'name' => 'name-test-1',
            'description' => 'description-test-1',
            'category' => [
                'id' => $category->id,
            ],
            'price' => 10000,
            'discount' => 100,
            'qty' => 100,
            'weight' => 100
        ]);
        Storage::fake('fake-images');

        $files = [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.jpg'),
            UploadedFile::fake()->image('photo3.jpg'),
        ];
    
        $response = $this->post(
            uri: '/api/v1/admin/products',
            data: [
                'payload' => $payload,
                'images' => $files,
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
     * @group products-id-get
     */
    public function test_product_id_get_success()
    {
        $token = $this->getToken(true);
        $products = Product::limit(1)->get();
        $product = $products[0];
        $response = $this->get(
            uri: '/api/v1/admin/products/'.$product->id,
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'sku',
                'name',
                'description',
                'category' => [
                    'id', 'name', 'description'
                ],
                'product_image' => [
                    '*' => [
                        'id', 'image_url'
                    ]
                ],
                'price',
                'discount',
                'qty',
                'weight',
                'sell_out',
                'created_at',
                'modified_at'
            ]);
    }

    /**
     * @group products-id-get
     */
    public function test_product_id_get_401()
    {
        $token = $this->getToken(true);
        $products = Product::limit(1)->get();
        $product = $products[0];
        $response = $this->get(
            uri: '/api/v1/admin/products/'.$product->id,
        );

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => 'UnAuthenticated'
                ]
            ]);
    }

    /**
     * @group products-id-get
     */
    public function test_product_id_get_403()
    {
        $token = $this->getToken(false);
        $products = Product::limit(1)->get();
        $product = $products[0];
        $response = $this->get(
            uri: '/api/v1/admin/products/'.$product->id,
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
     * @group products-update
     */
    public function test_product_id_update_success_1_field()
    {
        $token = $this->getToken(true);
        $categories = ProductCategory::limit(1)->get();
        $category = $categories[0];

        $products = Product::limit(1)->get();
        $product = $products[0];
        $payload = json_encode([
            'sku' => 'sku-update-test-1',
            'name' => 'name-update-test-1',
            'description' => 'description-update-test-1',
            'category' => [
                'id' => $category->id,
            ],
            'price' => 20000,
            'discount' => 200,
            'qty' => 200,
            'sell_out' => 10,
            'weight' => 200
        ]);
        $response = $this->patch(
            uri: '/api/v1/admin/products/'.$product->id,
            data: [
                'payload' => $payload
            ],
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'sku',
                    'name',
                    'description',
                    'category' => [
                        'id', 'name', 'description'
                    ],
                    'product_image' => [
                        '*' => [
                            'id', 'image_url'
                        ]
                    ],
                    'price',
                    'discount',
                    'qty',
                    'weight',
                    'sell_out',
                    'created_at',
                    'modified_at'
                ]
            ])
            ->assertJsonFragment([
                'sku' => 'sku-update-test-1',
                'name' => 'name-update-test-1',
                'description' => 'description-update-test-1',
                'price' => 20000,
                'discount' => 200,
                'qty' => 200,
                'sell_out' => 10,
                'weight' => 200
            ]);
    }

    /**
     * @group products-update
     */
    public function test_product_id_update_success_only_payload()
    {
        $token = $this->getToken(true);
        $categories = ProductCategory::limit(1)->get();
        $category = $categories[0];

        $products = Product::limit(1)->get();
        $product = $products[0];
        $payload = json_encode([
            'description' => 'description-update-test-1',
        ]);
        $response = $this->patch(
            uri: '/api/v1/admin/products/'.$product->id,
            data: [
                'payload' => $payload,
                'images[]'
            ],
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'sku',
                    'name',
                    'description',
                    'category' => [
                        'id', 'name', 'description'
                    ],
                    'product_image' => [
                        '*' => [
                            'id', 'image_url'
                        ]
                    ],
                    'price',
                    'discount',
                    'qty',
                    'weight',
                    'sell_out',
                    'created_at',
                    'modified_at'
                ]
            ])
            ->assertJsonFragment([
                'description' => 'description-update-test-1'
            ]);
    }

    /**
     * @group products-update
     */
    public function test_product_id_update_success_all_include_images()
    {
        // Upload
        $token = $this->getToken(true);
        $categories = ProductCategory::limit(1)->get();
        $category = $categories[0];
        $payload = json_encode([
            'sku' => 'sku-test-1',
            'name' => 'name-test-1',
            'description' => 'description-test-1',
            'category' => [
                'id' => $category->id
            ],
            'price' => 10000,
            'discount' => 100,
            'qty' => 100,
            'weight' => 100
        ]);

        $files = [
            new UploadedFile(base_path('tests/Fixtures/test-1.webp'), 'test-1.webp', null, null, true),
            new UploadedFile(base_path('tests/Fixtures/test-2.webp'), 'test-2.webp', null, null, true),
        ];
    
        $res = $this->post(
            uri: '/api/v1/admin/products',
            data: [
                'payload' => $payload,
                'images' => $files,
            ],
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        /**
         *  Update TEST
         */

        $filesUpdate = [
            UploadedFile::fake()->image('update1.jpg'),
            UploadedFile::fake()->image('update2.jpg'),
            UploadedFile::fake()->image('update3.jpg'),
        ];

        $productId = $res['data']['id'];
        $product = Product::where('id', $productId)->first();
        $productImages = $product->productImage;
        $deleteIds = [];
        foreach ($productImages as $image) {
            array_push($deleteIds, $image->id);
        }
        $payloadUpdate = json_encode([
            'sku' => 'sku-update-test-2',
            'name' => 'name-update-test-2',
            'description' => 'description-update-test-2',
            'category' => [
                'id' => $category->id,
            ],
            'price' => 30000,
            'discount' => 300,
            'sell_out' => 300,
            'qty' => 300,
            'weight' => 300,
            'deleteIds' => $deleteIds
        ]);

        $response = $this->patch(
            uri: '/api/v1/admin/products/'.$productId,
            data: [
                'payload' => $payloadUpdate,
                'images' => $filesUpdate
            ],
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'sku',
                    'name',
                    'description',
                    'category' => [
                        'id', 'name', 'description'
                    ],
                    'product_image' => [
                        '*' => [
                            'id', 'image_url'
                        ]
                    ],
                    'price',
                    'discount',
                    'qty',
                    'weight',
                    'sell_out',
                    'created_at',
                    'modified_at'
                ]
            ])
            ->assertJsonFragment([
                'sku' => 'sku-update-test-2',
                'name' => 'name-update-test-2',
                'description' => 'description-update-test-2',
                'price' => 30000,
                'discount' => 300,
                'sell_out' => 300,
                'qty' => 300,
                'weight' => 300,
            ]);

        // check if images update exist in storage
        $productUpdated = Product::where('id', $productId)->first();
        $productImages = $productUpdated->productImage;
        $imageExist = true;
        foreach ($productImages as $item) {
            Log::debug('is image exist = '.json_encode($item->name));
            if (!Storage::disk('public')->exists($item->path)) {
                $imageExist = false;
            }
        }
        self::assertTrue($imageExist);
    }

    /**
     * @group products-update
     */
    public function test_product_id_update_400_will_not_trigger()
    {
        $token = $this->getToken(true);
        $productId = Product::limit(1)->first()->id;
        $response = $this->patch(
            uri: '/api/v1/admin/products/'.$productId,
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200);
    }

    /**
     * @group products-update
     */
    public function test_product_id_update_401()
    {
        $token = $this->getToken(true);
        $productId = Product::limit(1)->first()->id;
        $response = $this->patch(
            uri: '/api/v1/admin/products/'.$productId,
        );

        $response->assertStatus(401)->assertJson([
            'errors' => [
                'message' => 'UnAuthenticated'
            ]
        ]);
    }

    /**
     * @group products-update
     */
    public function test_product_id_update_403()
    {
        $token = $this->getToken(false);
        $productId = Product::limit(1)->first()->id;
        $response = $this->patch(
            uri: '/api/v1/admin/products/'.$productId,
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(403)->assertJson([
            'errors' => [
                'message' => 'Forbidden, you do not have access'
            ]
        ]);
    }

    /**
     * @group products-delete
     */
    public function test_product_id_delete()
    {
        $token = $this->getToken(true);
        $productId = Product::limit(1)->first()->id;
        $response = $this->delete(
            uri: '/api/v1/admin/products/'.$productId,
            headers: [
                'Authorization' => 'Bearer '.$token
            ]
        );

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->has('message')
        );
    }

    /**
     * @group products-delete
     */
    public function test_product_id_delete_401()
    {
        $token = $this->getToken(false);
        $productId = Product::limit(1)->first()->id;
        $response = $this->delete(
            uri: '/api/v1/admin/products/'.$productId,
        );

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => 'UnAuthenticated'
                ]
            ]);
    }

    /**
     * @group products-delete
     */
    public function test_product_id_delete_403()
    {
        $token = $this->getToken(false);
        $productId = Product::limit(1)->first()->id;
        $response = $this->delete(
            uri: '/api/v1/admin/products/'.$productId,
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
}
