<?php

namespace App\Http\Controllers;

use App\Enums\ServerStatus;
use App\Models\JobPost;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{

    public function index()
    {
        $applications = Application::all();
        return response()->json($applications);
    }

    public function show($id)
    {
        $application = Application::findOrFail($id);
        return response()->json($application);
    }

    public function store(StoreApplicationRequest $request)
    {
        $validated = $request->validated();

        if (!$request->hasFile('resume_path')) {
            return response()->json(['error' => 'Please upload a resume.'], 400);
        }

        // Store the resume file
        $resumePath = '/storage/candidate-resumes/' . $request->file('resume_path')->store('resumes', 'public');
        $validated['resume_path'] = $resumePath;

        // Create and save the application
        $application = Application::create($validated);

        return response()->json($application)->setStatusCode(201);
    }


    public function destroy($id, Request $request)
    {
        $application = Application::findOrFail($id);
        if ($request->user()->id !== $application->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $application->delete();
        return response()->json(['message' => 'The application deleted'], 204);
    }

    public function approve(Application $application, UpdateApplicationRequest $request, $id)
    {
        $application = Application::findOrFail($id);
        $jobPostId = $application->job_post_id;
        $jobPost = JobPost::findOrFail($jobPostId);
        if ($request->user()->id !== $jobPost->user_id) {
            return response()->json(['PostOwner' => $jobPost->user_id, 'currentuser' => $request->user()->id], 403);
        }
        $application->update(['status' => 'accepted']);
        return response()->json(['message' => 'Application approved']);
    }
    public function reject(Application $application, UpdateApplicationRequest $request, $id)
    {
        $application = Application::findOrFail($id);
        $jobPostId = $application->job_post_id;
        $jobPost = JobPost::findOrFail($jobPostId);
        if ($request->user()->id !== $jobPost->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $application->update(['status' => 'rejected']);
        return response()->json(['message' => 'Application rejected']);
    }

    public function applicationsSubmittedToEmployer(Request $request)
    {
        $applications = $request->user()->postedJobs()->with('applications.applicant')->get()->pluck('applications')->flatten();

        $applications = $applications->map(function ($application) {
            return [
                'id' => $application->id,
                'resume_path' => $application->resume_path,
                'status' => $application->status,
                'applied_at' => $application->created_at,
                'applicant' => [
                    'id' => $application->applicant->id,
                    'name' => $application->applicant->name,
                    'email' => $application->applicant->email,
                    'phone_number' => $application->applicant->phone_number,
                    'profile_image' => $application->applicant->profile_image,
                ],
                'job_post' => [
                    'id' => $application->jobPost->id,
                    'title' => $application->jobPost->title,
                    'description' => $application->jobPost->description,
                    'requirements' => $application->jobPost->requirements,
                    'min_salary' => $application->jobPost->min_salary,
                    'max_salary' => $application->jobPost->max_salary,
                    'city' => $application->jobPost->cty,
                    'country' => $application->jobPost->country,
                    'min_exp_years' => $application->jobPost->min_exp_years,
                    'max_exp_years' => $application->jobPost->max_exp_years,
                    'expires_at' => $application->jobPost->expires_at,
                    'type' => $application->jobPost->type,
                    'remote_type' => $application->jobPost->remote_type,
                    'experience_level' => $application->jobPost->experience_level,
                    'status' => $application->jobPost->status
                ],
            ];
        });

        return response()->json($applications);
    }
}
