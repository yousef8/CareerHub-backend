<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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
            $imageUrl = Cloudinary::upload($request->file('profile_image')->getRealPath())->getSecurePath();
            $validRequest['profile_image'] = $imageUrl;
        }

        if ($request->hasFile('cover_image')) {
            $imageUrl = Cloudinary::upload($request->file('cover_image')->getRealPath())->getSecurePath();
            $validRequest['cover_image'] = $imageUrl;
        }

        $user = User::create($validRequest);
        return response()->json($user)->setStatusCode(201);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'No user with such id'], 404);
        }
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request)
    {
        $validRequest = $request->validated();

        if ($request->hasFile('profile_image')) {
            $imageUrl = Cloudinary::upload($request->file('profile_image')->getRealPath())->getSecurePath();
            $validRequest['profile_image'] = $imageUrl;

            if ($request->user()->profile_image) {
                Storage::disk('public')->delete('profile-images/' . basename($request->user()->profile_image));
            }
        }

        if ($request->hasFile('cover_image')) {
            $imageUrl = Cloudinary::upload($request->file('cover_image')->getRealPath())->getSecurePath();
            $validRequest['cover_image'] = $imageUrl;

            if ($request->user->profile_image) {
                Storage::disk('public')->delete('cover-images/' . basename($request->user->cover_image));
            }
        }

        $request->user->update($validRequest);
        return response()->json($request->user);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'No user with such id'], 404);
        }
        $user->deleteOrFail();
        return response()->json()->setStatusCode(204);
    }
}
