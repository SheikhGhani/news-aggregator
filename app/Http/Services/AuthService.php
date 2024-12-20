<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class AuthService extends BaseService
{
    /**
     * Register a new user.
     */
    public function register($request)
    {
        try {
            // Validate the incoming request using the request class
            $payload = $request->validated();

            // Wrap the DB operation in a transaction to ensure atomicity
            DB::beginTransaction();

            $user = User::create([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'password' => Hash::make($payload['password']),
            ]);

            // Commit the transaction if everything is fine
            DB::commit();

            // Return success response in JSON
            return $this->sendSuccessResponseJson(
                $user, 
                'User registered successfully', 
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollBack();

            // Return error response in JSON
            return $this->sendErrorResponseJson(
                'User registration failed', 
                [$e->getMessage()], 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * User login.
     */
    public function login($request)
    {
        try {
            // Validate the incoming request using the request class
            $payload = $request->validated();

            if (!Auth::attempt($payload)) {
                return $this->sendErrorResponseJson(
                    'Invalid credentials', 
                    [], 
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $user = Auth::user();

            // Return success response with user and token in JSON
            return $this->sendSuccessResponseJson(
                [
                    'user' => $user,
                    'token' => $user->createToken('auth_token')->plainTextToken
                ],
                'Login successful'
            );
        } catch (\Exception $e) {
            // Return error response in JSON
            return $this->sendErrorResponseJson(
                'Login failed', 
                [$e->getMessage()], 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * User logout.
     */
    public function logout($request)
    {
        try {
            // Revoke all tokens for the current user
            $request->user()->tokens()->delete();

            // Return success response in JSON
            return $this->sendSuccessResponseJson(
                null, 
                'Logged out successfully', 
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            // Return error response in JSON
            return $this->sendErrorResponseJson(
                'Logout failed', 
                [$e->getMessage()], 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    /**
     * Password reset request.
    */
    public function forgotPassword($request)
    {
        try {
            // Validate the incoming request using the request class
            $payload = $request->validated();

            // Send the password reset link
            Password::sendResetLink($payload);

            // Return success response in JSON
            return $this->sendSuccessResponseJson(
                null, 
                'Password reset link sent successfully', 
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            // Return error response in JSON
            return $this->sendErrorResponseJson(
                'Failed to send password reset link', 
                [$e->getMessage()], 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
