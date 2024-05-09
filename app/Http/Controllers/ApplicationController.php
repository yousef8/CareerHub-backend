<?php

namespace App\Http\Controllers;

use App\Enums\ServerStatus;

use App\Models\Application;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;

class ApplicationController extends Controller
{
    public function index()
    {
        $application = Application::all();
        return response()->json($application);
    }

    public function store(StoreApplicationRequest $request)
    {
        // Validate the incoming request data
        $validated = $request->validated();
    
        if (!isset($validated['job_id'])) {
            return response()->json(['error' => 'Please select a job before applying.'], 400);
        }
    
        if ($request->hasFile('resume_path')) {
            $validated['resume_path'] = '/storage/candidate-resumes/' . $request->file('resume_path')->store('resumes', 'public');
        }
      // Create and save the application
    $application = Application::create($validated);

    return response()->json($application)->setStatusCode(201);
    }
    
    
    public function show(Application $application)
    {
        return response()->json($application);
    }

    public function update(UpdateApplicationRequest $request, Application $application)
    {
        $validated = $request->validated();
        // dd($validated);
        // dd($application);
    
        if ($request->hasFile('resume_path')) {
            $validated['resume_path'] = '/storage/candidate-resumes/' . $request->file('resume_path')->store('resumes', 'public');
    
            // Delete old resume file if exists
            if ($application->resume_path) {
                Storage::disk('public')->delete('resumes/' . basename($application->resume_path));
            }
        }
    
        $application->update($validated);
    
        return response()->json($application)->setStatusCode(200);
    }
    
    

    public function destroy(Application $application)
    {
        $application->delete();

        return response()->json(['message'=>"the application deleted",'status_code'=>204], 204);
    }
}
