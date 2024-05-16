<?php

namespace App\Http\Controllers;

use App\Enums\ServerStatus;
use Illuminate\Http\Request;
use App\Http\Requests\StoreJobPostRequest;
use App\Http\Requests\UpdateJobPostRequest;
use App\Models\JobPost;
use App\Models\Skill;
use App\Models\Industry;


class JobPostController extends Controller
{
    public function unApproved()
    {
        $jobPosts = JobPost::where('is_approved', 0)->get();
        return response()->json($jobPosts);
    }

    public function index()
    {
        $jobPosts = JobPost::where('is_approved', 1)->get();
        for($i = 0; $i < count($jobPosts); $i++) {
            $jobPosts[$i]->applicants_count = $jobPosts[$i]->appliedUsers()->count();
            $jobPosts[$i]->skills = join(',', $jobPosts[$i]->skills->pluck('id', 'name')->toArray());
            $jobPosts[$i]->industries = join(',', $jobPosts[$i]->industries->pluck('id', 'name')->toArray());
        }
        return response()->json($jobPosts);
    }

    public function store(StoreJobPostRequest $request)
{
    $validatedData = $request->validated();
    $validatedData['is_approved'] = 0;
    $requestSkills = explode(',',$request->skills);
    $requestIndustries = explode(',',$request->industries);
    $skillIds = [];
    $industryIds = [];

    foreach ($requestSkills as $skillName) {
      $skill = Skill::where('name', strtolower($skillName))->first();
      if (!$skill) {
          $skill = Skill::create(['name' => strtolower($skillName)]);
      }
      $skillIds[] = $skill->id;
    }

    foreach ($requestIndustries as $industryName) {
      $industry = Industry::where('name', strtolower($industryName))->first();
      if (!$industry) {
          $industry = Industry::create(['name' => strtolower($industryName)]);
      }
      $industryIds[] = $industry->id;
    }

    $jobPost = $request->user()->postedJobs()->create($validatedData);
    $jobPost->skills()->attach($skillIds);
    $jobPost->industries()->attach($industryIds);
    return response()->json($jobPost, 201);
}


    public function show($id) 
    {
        $jobPost = JobPost::findOrFail($id);
        $numberOfApplications = $jobPost->appliedUsers()->count();
        $jobPost->applicants_count = $numberOfApplications;
        $jobPost->skills = join(',', $jobPost->skills->pluck('id', 'name')->toArray());
        $jobPost->industries = join(',', $jobPost->industries->pluck('id', 'name')->toArray());
        return response()->json($jobPost);
    }

    public function update(UpdateJobPostRequest $request, $id)
    {
        $jobPost = JobPost::findOrFail($id);
        if($request->user()->id !== $jobPost->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validatedData = $request->validated();
        $jobPost->update($validatedData);

        $requestSkills = explode(',',$request->skills);
        $requestIndustries = explode(',',$request->industries);
        $skillIds = [];
        $industryIds = [];

        foreach ($requestSkills as $skillName) {
          $skill = Skill::where('name', strtolower($skillName))->first();
          if (!$skill) {
              $skill = Skill::create(['name' => strtolower($skillName)]);
          }
          $skillIds[] = $skill->id;
        }
    
        foreach ($requestIndustries as $industryName) {
          $industry = Industry::where('name', strtolower($industryName))->first();
          if (!$industry) {
              $industry = Industry::create(['name' => strtolower($industryName)]);
          }
          $industryIds[] = $industry->id;
        }

        $jobPost->skills()->sync($skillIds);
        $jobPost->industries()->sync($industryIds);
        return response()->json($jobPost);
    }

    public function approve(UpdateJobPostRequest $request, $id)
    {
        $jobPost = JobPost::findOrFail($id);
        $jobPost->update(['is_approved' => 1]);
        return response()->json($jobPost);
    }

    
    public function destroy(Request $request,$id)
    {
      $jobPost = JobPost::findOrFail($id);
      if($request->user()->id !== $jobPost->user_id) {
        return response()->json(['message' => 'Unauthorized'], 403);
      }
        $jobPost->delete();
        return response()->json(['message' => 'Job post deleted successfully']);
    }

    public function search(Request $request)
    {
        $keywords = $request->get('keywords');
        $city = $request->get('city'); 
        $country = $request->get('country'); 
        $type = $request->get('type');
        $remote_type = $request->get('remote_type');
        $experienceLevel = $request->get('experience_level');
        $minSalary = $request->get('min_salary');
        $maxSalary = $request->get('max_salary');
        $postedAfter = $request->get('posted_after');
        $skills = $request->get('skills');
        $industries = $request->get('industries');

        $jobs = $this->buildSearchQuery($keywords, $city, $country,$type, $remote_type, $experienceLevel, $minSalary, $maxSalary, $postedAfter, $skills, $industries);

        return response()->json($jobs);
    }

    private function buildSearchQuery($keywords, $city, $country, $type, $remote_type, $experienceLevel, $minSalary, $maxSalary, $postedAfter, $skills, $industries)
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
        

      if ($remote_type) {
        $query->where('remote_type', $remote_type); 
      }

      if ($type) {
        $query->where('type', $type); 
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

      if ($skills) {
          $query->with('skills');
          $requiredSkills = explode(' ', $skills);
          $query->whereHas('skills', function ($query) use ($requiredSkills) {
            $query->whereIn('name', $requiredSkills);
        });
      }

      if ($industries) {
        $query->with('industries');
        $Industries = explode(' ', $industries);
        $query->whereHas('industries', function ($query) use ($Industries) {
          $query->whereIn('name', $Industries);
      });
    }

      if (config('app.debug')) {
        \Log::debug('Search Query: ' . $query->toSql());
      }
    
      return $query->where('is_approved', 1)->get();
    }
    
}
