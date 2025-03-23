<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use App\Rules\ReachableUrl;
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
            'lifespan'        => 'required|string|max:255',
        ]);

        $leader = Leader::create($validatedData);
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
            'lifespan'        => 'required|string|max:255',
        ]);

        $leader->update($validatedData);
        return response()->json($leader);
    }

    // Delete a leader.
    public function destroy(Leader $leader): \Illuminate\Http\JsonResponse
    {
        $leader->delete();
        return response()->json(null, 204);
    }
}
