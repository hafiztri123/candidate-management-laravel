<?php

namespace App\Http\Controllers\Api;

use App\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponder;

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect'],
            ]);
        }

        return $this->successResponse($user->createToken($request->device_name)->plainTextToken, 'Login success');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, null, 204);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                'string',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->mixedCase()
            ]
            ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $userRole = Role::where('slug', 'user')->first();
        $user->roles()->attach($userRole);


        return $this->successResponse(null, 'Register success', 201);

    }

    public function getSelfProfile(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->unread_notifications_count ?? 0;
        $user['unread_notification'] = $notifications;

        return $this->successResponse($user, 'Self profile', 200);
    }
}
