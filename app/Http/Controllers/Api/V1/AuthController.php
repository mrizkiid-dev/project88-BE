<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Utils\ResponseErrorHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    public function login(AuthLoginRequest $request)
    {
        try {
            $request->validated();

            $credentials = $request->only('email', 'password');
            $isSuccess = Auth::attempt($credentials);
            
            if (!$isSuccess) {
                throw new HttpException(401, 'Unauthorized');
            }

            // if(Auth::User()->role->pluck('role_name')->)
            $token = Auth::user()->createToken('token')->plainTextToken;

            $user = Auth::user();
            return response()->json([
                    'status' => 'success',
                    'user' => new UserResource($user),
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
        } catch (\Throwable $e) {
            return ResponseErrorHelper::throwErrorResponse($e);
        }   
    }

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

            $roles = Role::where('role_name', RoleEnum::USER)->first();
            Log::info(['roles = ',$roles]);
            $user->role()->attach($roles->id);

            DB::commit();
            // $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user
            ],201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return ResponseErrorHelper::throwErrorResponse($e);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'success' => 'true',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
