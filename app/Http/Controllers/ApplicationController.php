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
        $applications = Application::with('applicant', 'jobPost')->get();
        $applications->each(function ($application) {
            $application->makeHidden(['user_id', 'job_post_id']);
            if ($application->applicant) {
                $application->applicant->makeHidden(['pivot', 'email_verified_at', 'updated_at', 'created_at']); // Hide pivot if exists
            }
            if ($application->jobPost) {
                $application->jobPost->makeHidden(['user_id', 'created_at', 'updated_at']);
            }
        });

        return response()->json($applications);
    }

    public function show($id)
    {
        $application = Application::findOrFail($id);
        return response()->json($application);
    }

    public function store(StoreApplicationRequest $request, $id)
    {
        $jobPost = JobPost::findOrFail($id);
        $validatedRequest = $request->validated();

        $resume = null;
        if ($request->hasFile('resume_path')) {
            $resume = '/storage/candidate-resumes/' . $request->file('resume_path')->store('resumes', 'public');
        }
        if ($request->user()->appliedJobs->contains($jobPost->id)) {
            return response()->json(['message' => 'You have already applied for this job post'], 422);
        }

        $application = $jobPost->applications()->create(['resume_path' => $resume, 'user_id' => $request->user()->id]);

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

    public function approve(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->approve();
        return response()->json(['message' => 'Application approved']);
    }

    public function reject(Application $application, UpdateApplicationRequest $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->reject();
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
