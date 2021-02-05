<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index(User $user)
    {
        try {
            return response()->json([
                'users' => $user->orderBy('updated_at', 'desc')->get()
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Something went wrong on our end!'
            ], 500);
        }
    }

    public function show(User $user)
    {
        try {
            return response()->json(['user' => $user], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Something went wrong on our end!'
            ], 500);
        }
    }

    public function create(Request $request, User $user)
    {
        $validator = Validator::make($request->only(['name', 'email']), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validaton Error',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user_data = $request->only(['name', 'email']);
            $user->create($user_data);

            return response()->json([
                'message' => 'User created sucessfully.'
            ], 201);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Something went wrong on our end!'
            ], 500);
        }
    }

    public function delete(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'message' => 'User deleted sucessfully.'
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Something went wrong on our end!'
            ], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->only(['name', 'email']), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validaton Error',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user_data = $request->only(['name', 'email']);
            $user->update($user_data);

            return response()->json([
                'message' => 'User updated sucessfully.'
            ], 201);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Something went wrong on our end!'
            ], 500);
        }
    }
}
