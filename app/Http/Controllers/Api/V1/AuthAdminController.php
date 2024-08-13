<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Utils\ResponseErrorHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthAdminController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $request->validated();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $roles = Role::where('role_name', RoleEnum::ADMIN)->first();
            $user->role()->attach($roles->id);

            DB::commit();
            // $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => new UserResource($user)
            ],201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return ResponseErrorHelper::throwErrorResponse($e);
        }
    }

}
