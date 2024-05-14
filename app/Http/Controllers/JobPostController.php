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
      $validatedData['is_approved'] = 0;
      $jobPost = $request->user()->postedJobs()->create($validatedData);
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
        $city = $request->get('city'); 
        $country = $request->get('country'); 
        $category = $request->get('category');
        $experienceLevel = $request->get('experience_level');
        $minSalary = $request->get('min_salary');
        $maxSalary = $request->get('max_salary');
        $postedAfter = $request->get('posted_after');

        $jobs = $this->buildSearchQuery($keywords, $city, $country, $category, $experienceLevel, $minSalary, $maxSalary, $postedAfter);

        return response()->json($jobs);
    }

    private function buildSearchQuery($keywords, $city, $country, $category, $experienceLevel, $minSalary, $maxSalary, $postedAfter)
    {
      $query = JobPost::query();
    

        if ($keywords) {
            $keywords = explode(' ', $keywords);
            $query->where(function ($query) use ($keywords) {
              foreach ($keywords as $keyword) {
                $query->orWhere(function ($subquery) use ($keyword) {
                  $subquery->where('title', 'LIKE', "%{$keyword}%")
                           ->orWhere('description', 'LIKE', "%{$keyword}%");
                });
              }
            });
        }
           
        if ($city) {
            $query->where('city', 'LIKE', "%{$city}%");
        }
        
        if ($country) {
            $query->where('country', 'LIKE', "%{$country}%");
        }
        

      if ($category) {
        $query->where('category', $category); 
      }
    
  
      if ($experienceLevel) {
        $query->where('experience_level', $experienceLevel);
      }
    
       
      if ($minSalary) {
        $minSalary = intval($minSalary);
        $query->where('min_salary' , '>=', $minSalary);
      }

      if ($maxSalary) {
        $maxSalary = intval($maxSalary);
        $query->where('max_salary' , '<=', $maxSalary);
      }
      
      
      if ($postedAfter) {
        $query->whereDate('created_at', '>=', $postedAfter);
      }
    
      
      if (config('app.debug')) {
        \Log::debug('Search Query: ' . $query->toSql());
      }
    
      return $query->get();
    }
    





    
}
