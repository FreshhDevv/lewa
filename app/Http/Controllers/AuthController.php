<?php

namespace App\Http\Controllers;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @group Authentication
 *
 * API auth endpoints
 * */

class AuthController extends Controller
{
    /**
 * Login.
 * @header Authorization Bearer {token} required A valid Sanctum token.

 * @authenticated
 * @response 200 {
 * "status": "true",
 * "message": "User logged in successfully.",
 * "user": {
 *    "id": 5,
 *   "firstName": "Dr",
 *   "lastName": "Gonzo",
 *    "email": "gonzo@gmail.com",
 *   "profilePicture": null,
 *   "status": "active",
 *   "created_at": "2023-07-16T07:25:15.000000Z",
 *   "updated_at": "2023-07-16T07:25:15.000000Z"
 *  },
 * "role": "coordinator",
 * "accessToken": "2|nkdOWNVEkN7098JhvpeCqzxQgA2zV60KgsBhkjjq",
 * "tokenType": "Bearer"
 * }
 *
 * @response 401 {
 *      "message": "Unauthenticated."
 * }
 *
 *
 */
    public function login(AuthRequest $request)
    {
        $validated = $request->validate((new AuthRequest())->rules());
        $user = User::where('email', $validated['email'])->first();

        if (!Hash::check($validated['password'], $user->password)) {
            return response([
                'status' => 'false',
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response(
            [
                'status' => 'true',
                'message' => 'User logged in successfully.',
                'user' => $user,
                'role' => $user->roles->first()->name,
                'accessToken' => $token,
                'tokenType' => 'Bearer',
            ]
        );
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();

        if (!Hash::check($validated['old_password'], auth()->user()->password)) {
            return response()->json(['message' => 'Old password does not match existing password'], 400);
        } elseif (Hash::check($validated['new_password'], auth()->user()->password)) {
            return response()->json(['message' => 'New password is the same as old password'], 400);
        }

            User::findOrFail(auth()->user()->id)->update([
                'password' => bcrypt($validated['new_password']),
            ]);

        return response()->json(['user' => 'Password Changed Successfully']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'message' => 'Logged Out',
        ];
    }
}
