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

    public function search(Request $request)
    {
        $keywords = $request->get('keywords');
        $location = $request->get('location'); // Can be city, state, country etc.
        $category = $request->get('category'); // Industry or Job Category
        $experienceLevel = $request->get('experience_level');
        $minSalary = $request->get('min_salary');
        $maxSalary = $request->get('max_salary');
        $postedAfter = $request->get('posted_after'); // Date after which jobs were posted

        $jobs = $this->buildSearchQuery($keywords, $location, $category, $experienceLevel, $minSalary, $maxSalary, $postedAfter);

        return response()->json($jobs);
    }

    private function buildSearchQuery($keywords, $location, $category, $experienceLevel, $minSalary, $maxSalary, $postedAfter)
    {
      $query = JobPost::query();
    
      // Filter by keywords (exact title match)
      if ($keywords) {
        $query->where('title', 'LIKE', "%{$keywords}%");
      }
    
      // Filter by location
      if ($location) {
        $query->where('city', $location); // Or use a location matching logic based on your schema
      }
    
      // Filter by category
      if ($category) {
        $query->where('category', $category); // Or use a category matching logic based on your schema
      }
    
      // Filter by experience level
      if ($experienceLevel) {
        $query->where('experience_level', $experienceLevel);
      }
    
      // Filter by salary range (with additional checks)
      if ($minSalary && $maxSalary) {
        // Ensure data type consistency (assuming integer salary)
        $minSalary = intval($minSalary);
        $maxSalary = intval($maxSalary);
    
        // Handle empty values (optional, adjust based on your needs)
        if ($minSalary > 0 && $maxSalary > 0) {
          $query->whereBetween('min_salary', [$minSalary, $maxSalary]);
        } else {
          // Log or handle cases where one or both salaries are empty/invalid
          // (e.g., display a message to the user)
        }
      }
    
      // Filter by posted date
      if ($postedAfter) {
        $query->whereDate('created_at', '>=', $postedAfter);
      }
    
      // Enable logging for debugging (optional)
      if (config('app.debug')) {
        \Log::debug('Search Query: ' . $query->toSql());
      }
    
      return $query->get();
    }
    





    
}
