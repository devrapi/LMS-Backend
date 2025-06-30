<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use Tymon\JWTAuth\Exceptions\JWTException;
// use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
         // Validate the request
         $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors(), 401]);
        }


            DB::beginTransaction();

            // Attempt to verify the credentials
            $user = Admin::where('email', $request->email)->first();

            // Check if the user exists
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Check if the password is correct
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // if user exists add JWT token with custom claims that expires in 10 years
            // $token = JWTAuth::customClaims(['exp' => now()->addYears(10)->timestamp])->fromUser($user);

            DB::commit();

            // Return the token and user information
            return response()->json([
                'message' => 'Login successful',
                // 'token' => $token,
                'user' => $user,
            ], 200);


    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
         // Validate the request
         $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:password',
        ]);

        // Check if validation fails
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors(), 401]);
        };

        try {
            DB::beginTransaction();

            // Create a new user
            $user = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            DB::commit();
            return response()->json([
                'message' => 'User registered successfully',
                'status' => true,
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error occurred while processing your request, ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
