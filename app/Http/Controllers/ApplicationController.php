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
        $validatedData = $request->validated();

        $application = Application::create($validatedData);

        return response()->json($application, 201);
    }

    public function show(Application $application)
    {
        return response()->json($application);
    }

    public function update(UpdateApplicationRequest $request, Application $application)
    {
        $validatedData = $request->validated();

        $application->updateOrFail($validatedData);

        return response()->json($application, 200);
    }

    public function destroy(Application $application)
    {
        $application->delete();

        return response()->json(null, 204);
    }
}
