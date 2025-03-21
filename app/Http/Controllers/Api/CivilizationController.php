<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Civilization;
use Illuminate\Http\Request;

class CivilizationController extends Controller
{
    // Return a list of all civilizations.
    public function index(): \Illuminate\Http\JsonResponse
    {
        $civilizations = \App\Models\Civilization::with('leader')->get();
        return response()->json($civilizations);
    }

    // Store a new civilization.
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $civilization = Civilization::create($request->only(['name', 'icon']));
        return response()->json($civilization, 201);
    }

    // Show a specific civilization.
    public function show(Civilization $civilization): \Illuminate\Http\JsonResponse
    {
        $civilization->load('historicalInfo');
        return response()->json($civilization);
    }    

    // Update a civilization.
    public function update(Request $request, Civilization $civilization): \Illuminate\Http\JsonResponse
    {
        $civilization->update($request->only(['name', 'icon']));
        return response()->json($civilization);
    }

    // Delete a civilization.
    public function destroy(Civilization $civilization): \Illuminate\Http\JsonResponse
    {
        $civilization->delete();
        return response()->json(null, 204);
    }
}
