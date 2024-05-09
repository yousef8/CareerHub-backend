<?php

namespace App\Http\Controllers;

use App\Enums\ServerStatus;
use Illuminate\Http\Request;
use App\Http\Requests\StoreJobPostRequest;
use App\Http\Requests\UpdateJobPostRequest;
use App\Models\JobPost;


class JobPostController extends Controller
{
    public function index()
    {
        $jobPosts = JobPost::all();
        return response()->json($jobPosts);
    }

    public function store(StoreJobPostRequest $request)
    {
        $validatedData = $request->validated();
        $jobPost = JobPost::create($validatedData);
        return response()->json($jobPost, 201);
    }

    public function show($id)
    {
        $jobPost = JobPost::findOrFail($id);
        return response()->json($jobPost);
    }

    public function update(UpdateJobPostRequest $request, $id)
    {
        $jobPost = JobPost::findOrFail($id);
        $validatedData = $request->validated();
        $jobPost->update($validatedData);
        return response()->json($jobPost);
    }
    
    public function destroy(Request $request,$id)
    {
        $jobPost = JobPost::findOrFail($id);
        $jobPost->delete();
        return response()->json(['message' => 'Job post deleted successfully']);
    }
    
}
