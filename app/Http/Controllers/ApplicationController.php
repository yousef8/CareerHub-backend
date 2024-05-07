<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function index()
    {
        // Fetch all applications from the database
        $applications = Application::all();
        return response()->json($applications);
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            // Define validation rules here
        ]);

        // Create a new application
        $application = Application::create($validatedData);

        return response()->json($application, 201);
    }

    public function show($id)
    {
        // Find the application by ID
        $application = Application::findOrFail($id);
        return response()->json($application);
    }

    public function update(Request $request, $id)
    {
        // Find the application by ID
        $application = Application::findOrFail($id);

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
        $application = Application::findOrFail($id);

        // Delete the application
        $application->delete();

        return response()->json(null, 204);
    }
}
