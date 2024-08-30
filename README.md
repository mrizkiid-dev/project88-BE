# about

1. This back-end is my personal project to make e-commerce named **project88** crated with **laravel 10**
2. I made the fronted with **NUXT-JS** and **Supabase** as a back-end before check on [project88.rizkidev.my.id][1]
3. [1]: https://project88.rizkidev.my.id
4. This repo is only about to make for admin,
5. My original back-end is from supabase so i need to migrate database **postgresSQL** to my local for developement purpose
****

# Requirement
1. PHP 8.3.6
2. PostgreSQL 10.4
3. Composer

****

# How to run

1. pull from main
2. create database in your local with postgreSQL
3. create environment in root directory project and set environment with your database detail
4. run php artisan migrate

#### if you want to test api on postman or another similiar tools like postman
1.  php artisan db:seed
2.  php artisan serve
3.  check on directory docs/ for open api documentation 
4.  you ready to test

#### i also use test on unit test for api
database will reset for every test run 
**_run all test_**
- php artisan test

**_run auth test_**
- php artisan test --filter=AuthTest 

**_run order test_**
- php artisan test --filter=OrderTest 

**_run product test_**
- php artisan test --filter=ProductTest 
