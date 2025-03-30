<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Civilization;
use App\Rules\ReachableUrl;
use App\Events\DataChanged;
use App\Events\CivilizationUpdated;
use App\Events\CivilizationDeleted;
use Illuminate\Http\Request;

class CivilizationController extends Controller
{
    // Return a list of all civilizations.
    public function index(): \Illuminate\Http\JsonResponse
    {
        $civilizations = Civilization::with('leader')->get();
        return response()->json($civilizations);
    }

    // Store a new civilization.
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => ['required', 'url', 'regex:/^https?:\/\//i', 'max:255', new ReachableUrl()],
        ]);

        $civilization = Civilization::create($validatedData);

        // Dispatch the DataChanged event to notify listeners about the new civilization creation.
        event(new DataChanged('A new civilization has been created!'));

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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => ['required', 'url', 'regex:/^https?:\/\//i', 'max:255', new ReachableUrl()],
        ]);

        $civilization->update($validatedData);
        
        // Dispatch the CivilizationUpdated event to notify listeners about the civilization update.
        event(new CivilizationUpdated($civilization));

        return response()->json($civilization);
    }

    // Delete a civilization.
    public function destroy(Civilization $civilization): \Illuminate\Http\JsonResponse
    {
        $civilization->delete();

        // Dispatch the DataChanged event to notify listeners about the civilization deletion.
        event(new CivilizationDeleted($civilization->id));

        return response()->json(null, 204);
    }
}
