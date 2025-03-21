<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use Illuminate\Http\Request;

class LeaderController extends Controller
{
    // Return a list of all leaders.
    public function index(): \Illuminate\Http\JsonResponse
    {
        $leaders = \App\Models\Leader::with('civilization')->get();
        return response()->json($leaders);    
    }

    // Store a new leader.
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $leader = Leader::create($request->only(['civilization_id', 'name', 'icon', 'subtitle', 'lifespan']));
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
        $leader->update($request->only(['civilization_id', 'name', 'icon', 'subtitle', 'lifespan']));
        return response()->json($leader);
    }

    // Delete a leader.
    public function destroy(Leader $leader): \Illuminate\Http\JsonResponse
    {
        $leader->delete();
        return response()->json(null, 204);
    }
}
