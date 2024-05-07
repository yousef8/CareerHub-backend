<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applications;

class ApplicationController extends Controller
{
    public function index()
    {
        // Fetch all applications from the database
        $applications = Applications::all();
        return response()->json($applications);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:job_posts,id',
            'resume_path' => 'required|string',
            'status' => 'required|string',
        ]);

        // Create the application
        $application = Application::create($validatedData);

        return response()->json($application, 201);
    }

    public function show($id)
    {
        // Find the application by ID
        $application = Applications::findOrFail($id);
        return response()->json($application);
    }

    public function update(Request $request, $id)
    {
        // Find the application by ID
        $application = Applications::findOrFail($id);

        // Validate incoming request data
        $validatedData = $request->validate([
            // Define validation rules here
        ]);

        // Update the application
        $application->update($validatedData);

        return response()->json($application, 200);
    }

    public function destroy($id)
    {
        // Find the application by ID
        $application = Applications::findOrFail($id);

        // Delete the application
        $application->delete();

        return response()->json(null, 204);
    }
}
