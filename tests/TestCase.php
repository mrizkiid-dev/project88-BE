<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        // DB::delete('delete from public.user_role'); 
        // DB::delete('delete from public.role'); 
        // DB::delete('delete from public.users');
        
        // DB::delete('delete from public.order'); 
        // DB::delete('delete from public.order_confirmation'); 
        // DB::delete('delete from public.order_item'); 
        // DB::delete('delete from public.product_image'); 
        // DB::delete('delete from public.product'); 
        // DB::delete('delete from public.product_category'); 
        // DB::delete('delete from public.shopping_session'); 
        // DB::delete('delete from public.user'); 
        // DB::delete('delete from public.user_address'); 
        // DB::delete('delete from public.users'); DB::table('users')->truncate();

        DB::table('user_role')->truncate();
        DB::table('role')->truncate();
        DB::table('users')->truncate();
        DB::table('order')->truncate();
        DB::table('order_confirmation')->truncate();
        DB::table('order_item')->truncate();
        DB::table('product_image')->truncate();
        DB::table('product')->truncate();
        DB::table('product_category')->truncate();
        DB::table('shopping_session')->truncate();
        DB::table('user')->truncate();
        DB::table('user_address')->truncate();
    }
}
