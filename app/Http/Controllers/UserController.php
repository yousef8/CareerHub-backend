<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(StoreUserRequest $request)
    {
        $validRequest = $request->validated();

        if ($request->hasFile('profile_image')) {
            $validRequest['profile_image'] = '/storage/' . $request->file('profile_image')->store('profile-images', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $validRequest['cover_image'] = '/storage/' . $request->file('cover_image')->store('cover-images', 'public');
        }

        $user = User::create($validRequest);
        return response()->json($user)->setStatusCode(201);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validRequest = $request->validated();

        if ($request->hasFile('profile_image')) {
            $validRequest['profile_image'] = '/storage/' . $request->file('profile_image')->store('profile-images', 'public');

            if ($user->profile_image) {
                Storage::disk('public')->delete('profile-images/' . basename($user->profile_image));
            }
        }

        if ($request->hasFile('cover_image')) {
            $validRequest['cover_image'] = '/storage/' . $request->file('cover_image')->store('cover-images', 'public');

            if ($user->profile_image) {
                Storage::disk('public')->delete('cover-images/' . basename($user->cover_image));
            }
        }

        $user->update($validRequest);
        return response()->json($user)->setStatusCode(200);
    }

    public function destroy(User $user)
    {
        $user->deleteOrFail();
        return response()->json()->setStatusCode(204);
    }
}
