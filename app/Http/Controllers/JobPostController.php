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
        $city = $request->get('city'); // Can be city, state, country etc.
        $country = $request->get('country'); // Can be city, state, country etc.
        $category = $request->get('category'); // Industry or Job Category
        $experienceLevel = $request->get('experience_level');
        $minSalary = $request->get('min_salary');
        $maxSalary = $request->get('max_salary');
        $postedAfter = $request->get('posted_after'); // Date after which jobs were posted

        $jobs = $this->buildSearchQuery($keywords, $city, $country, $category, $experienceLevel, $minSalary, $maxSalary, $postedAfter);

        return response()->json($jobs);
    }

    private function buildSearchQuery($keywords, $city, $country, $category, $experienceLevel, $minSalary, $maxSalary, $postedAfter)
    {
      $query = JobPost::query();
    
      // Filter by keywords (exact title match)
    //   if ($keywords) {
    //     $query->where('title', 'LIKE', "%{$keywords}%");
    //   }
     
        // Filter by keywords (title or description match)
        // if ($keywords) {
        //     $query->where(function ($query) use ($keywords) {
        //     $query->where('title', 'LIKE', "%{$keywords}%")
        //         ->orWhere('description', 'LIKE', "%{$keywords}%");
        //     });
        // }

        // Filter by keywords (title or description match)
        if ($keywords) {
            $keywords = explode(' ', $keywords); // Split keywords into an array
            $query->where(function ($query) use ($keywords) {
              foreach ($keywords as $keyword) {
                $query->orWhere(function ($subquery) use ($keyword) {
                  $subquery->where('title', 'LIKE', "%{$keyword}%")
                           ->orWhere('description', 'LIKE', "%{$keyword}%");
                });
              }
            });
        }
           
      
    
        // Filter by location
        // if ($location) {
        //     $query->where('city', $location); // Or use a location matching logic based on your schema
        // }

        // Filter by location (partial match)
        if ($city) {
            $query->where('city', 'LIKE', "%{$city}%");
        }
        
        if ($country) {
            $query->where('country', 'LIKE', "%{$country}%");
        }
        


      // Filter by category
      if ($category) {
        $query->where('category', $category); // Or use a category matching logic based on your schema
      }
    
      // Filter by experience level
      if ($experienceLevel) {
        $query->where('experience_level', $experienceLevel);
      }
    
        // Filter by salary range
      if ($minSalary) {
        $minSalary = intval($minSalary);
        $query->where('min_salary' , '>=', $minSalary);
      }

      if ($maxSalary) {
        $maxSalary = intval($maxSalary);
        $query->where('max_salary' , '<=', $maxSalary);
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
