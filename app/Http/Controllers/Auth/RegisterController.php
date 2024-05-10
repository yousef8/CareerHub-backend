<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', 'min:8'],
                'phone_number' => ['required', 'string', 'regex:/^01[0125][0-9]{8}$/'],
                'profile_image' => 'sometimes|image',
                'cover_image' => 'sometimes|image',
                'role' => [
                    'required',
                    Rule::in(['employer', 'candidate']),
                ]
            ],
            [
                'role.required' => "Must supply a role [employer, candidate]",
                'role.in' => 'Only [employer, candidate] roles are allowed'
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }
}
