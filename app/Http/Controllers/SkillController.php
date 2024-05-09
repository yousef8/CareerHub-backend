<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of skills.
     *
     * This method returns a list of skills from the database.
     * The list can be paginated and filtered by a search term.
     *
     * @param Request $request - The request object containing query parameters.
     *
     * @return JsonResponse - A JSON response containing paginated skills.
     *
     * Usage example:
     * GET /api/skills?search=programming&per_page=15
     * This would search for skills with 'programming' in their name
     * and return up to 15 results per page.
     */
    public function index(Request $request): JsonResponse
    {
        // Get query parameters for pagination and search
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10); // Default items per page
        $currentPage = $request->query('page', 1); // Default current page is 1

        // Create a base query
        $query = Skill::query();

        // Apply search if a search term is provided
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Paginate the results with the specified number of items per page and current page
        $skills = $query->paginate($perPage, ['*'], 'page', $currentPage);


        // Return the paginated skills
        return response()->json($skills);
    }

    /**
     * Store a new skill in the database.
     *
     * Only admins can create a new skill.
     *
     * @param Request $request - The request object containing the skill data.
     *
     * @return JsonResponse - A JSON response containing the created skill and a 201 status code.
     */
    public function store(Request $request): JsonResponse
    {
        // Authorization check: only allow admins to create a new skill
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|unique:skills,name',
        ]);

        $skill = Skill::create($request->only('name'));
        return response()->json($skill, 201);
    }

    /**
     * Display the specified skill.
     *
     * This method retrieves a specific skill by its ID.
     *
     * @param int $id - The ID of the skill to retrieve.
     *
     * @return JsonResponse - A JSON response containing the skill or a 404 not found message.
     *
     * Usage example:
     * GET /api/skills/1
     * This would retrieve the skill with an ID of 1 and return the skill object.
     */
    public function show(Skill $skill): JsonResponse
    {
        $skill = Skill::find($id);
        if (!$skill) {
            return response()->json(['message' => 'Skill not found'], 404);
        }
        return response()->json($skill);
    }

    /**
     * Update the specified skill.
     *
     * Only admins can update an existing skill by its ID.
     *
     * @param Request $request - The request object containing the updated skill data.
     * @param int $id - The ID of the skill to update.
     *
     * @return JsonResponse - A JSON response containing the updated skill or a 404 not found message.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Authorization check: only allow admins to update a skill
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $skill = Skill::find($id);

        if (!$skill) {
            return response()->json([
                'message' => 'Skill not found'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|unique:skills,name,' . $id,
        ]);

        $skill->update($request->only('name'));
        return response()->json($skill);
    }

    /**
     * Remove the specified skill from the database.
     *
     * Only admins can delete an existing skill by its ID.
     *
     * @param int $id - The ID of the skill to delete.
     *
     * @return JsonResponse - A JSON response containing a success message or a 404 not found message.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        // Authorization check: only allow admins to delete a skill
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $skill = Skill::find($id);

        if (!$skill) {
            return response()->json([
                'message' => 'Skill not found'
            ], 404);
        }

        $skill->delete();

        return response()->json([
            'message' => 'Skill deleted successfully'
        ]);
    }
}
