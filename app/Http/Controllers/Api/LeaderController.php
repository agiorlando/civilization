<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use App\Rules\ReachableUrl;
use App\Events\DataChanged;
use App\Events\LeaderUpdated;
use App\Events\LeaderDeleted;
use Illuminate\Http\Request;

class LeaderController extends Controller
{
    // Return a list of all leaders.
    public function index(): \Illuminate\Http\JsonResponse
    {
        $leaders = Leader::with('civilization')->get();
        return response()->json($leaders);
    }

    // Store a new leader.
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'civilization_id' => 'required|integer|exists:civilizations,id',
            'name'            => 'required|string|max:255',
            'icon'            => ['required', 'url', 'regex:/^https?:\/\//i', 'max:255', new ReachableUrl()],
            'subtitle'        => 'required|string|max:255',
            'life_start'      => 'nullable|string|max:20|required_without:life_end',
            'life_end'        => 'nullable|string|max:20|required_without:life_start',
        ]);

        $leader = Leader::create($validatedData);

        // Dispatch the DataChanged event to notify listeners about the new leader creation.
        event(new DataChanged('A new leader has been created!'));

        return response()->json($leader, 201);
    }

    // Show a specific leader.
    public function show(Leader $leader): \Illuminate\Http\JsonResponse
    {
        $leader->load(['civilization', 'historicalInfo']);
        return response()->json($leader);
    }

    // Update a leader.
    public function update(Request $request, Leader $leader): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'civilization_id' => 'required|integer|exists:civilizations,id',
            'name'            => 'required|string|max:255',
            'icon'            => ['required', 'url', 'regex:/^https?:\/\//i', 'max:255', new ReachableUrl()],
            'subtitle'        => 'required|string|max:255',
            'life_start'      => 'nullable|string|max:20|required_without:life_end',
            'life_end'        => 'nullable|string|max:20|required_without:life_start',
        ]);

        $leader->update($validatedData);
        
        // Dispatch the DataChanged event to notify listeners about the leader update.
        event(new LeaderUpdated($leader));

        return response()->json($leader);
    }

    // Delete a leader.
    public function destroy(Leader $leader): \Illuminate\Http\JsonResponse
    {
        $leader->delete();
        
        // Dispatch the DataChanged event to notify listeners about the leader deletion.
        event(new LeaderDeleted($leader->id));

        return response()->json(null, 204);
    }
}
