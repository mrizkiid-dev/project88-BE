<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthLoginTokenRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Utils\ResponseErrorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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
                throw new HttpException(401, 'User email and password not valid');
            }

            $user = Auth::user();
            return response()->json([
                    'success' => true,
                    'user' => new UserResource($user),
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

            $isUserExist = User::where('email', $request->email)->exists();
            if($isUserExist) {
                throw new HttpException(409, 'email already registered');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $roles = Role::where('role_name', RoleEnum::USER)->first();
            $user->role()->attach($roles->id);

            DB::commit();
            // $token = Auth::login($user);
            return response()->json([
                'success' => true,
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
        try {
            $user = Auth::user();
            $user->currentAccessToken()->delete();
            auth()->guard('web')->logout();
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out',
            ]);
        } catch (\Throwable $e) {
            return ResponseErrorHelper::throwErrorResponse($e);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $validated = $request->validate([
                'device_name' => ['required', 'string']
            ]);
            $user = Auth::user();
            if(!$user) {
                throw new HttpException(401, 'unauthorize');
            }
            
            $user->currentAccessToken()->delete();
            $token = $token = $user->createToken($validated['device_name'])->plainTextToken;
            if (!$token) {
                throw new HttpException(401, 'unauthorize');
            }
            
            return response()->json([
                'success' => true,
                'user' => new UserResource($user),
                'authorization' => [
                    'token' => $token,
                    'type' => 'Bearer',
                ]
            ]);
        } catch (\Throwable $e) {
            return ResponseErrorHelper::throwErrorResponse($e);
        }
    }

    public function loginToken(AuthLoginTokenRequest $request)
    {
        try {
            $request->validated();

            $user = User::where('email', $request->email)->first();
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Email and Password are incorrect.'],
                ]);
            }

            $token = $user->createToken($request->device_name)->plainTextToken;

            return response()->json([
                    'success' => true,
                    'user' => new UserResource($user),
                    'authorization' => [
                        'token' => $token,
                        'type' => 'Bearer',
                    ]
                ]);
        } catch (\Throwable $e) {
            return ResponseErrorHelper::throwErrorResponse($e);
        }  
    }
}
