<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use Inertia\Inertia;

class VoterController extends Controller
{
    public function index()
    {
        $voters = Voter::orderBy('name')->get()->map(fn ($voter) => [
            'id' => $voter->id,
            'name' => $voter->name,
            'email' => $voter->email,
            'course' => $voter->course,
            'destroy_url' => route('admin.voters.destroy', $voter, false),
        ])->values()->all();
        return Inertia::render('Admin/Voters', ['voters' => $voters]);
    }

    public function destroy(Voter $voter)
    {
        $voter->delete();
        return redirect()->route('admin.voters.index')->with('success', 'Voter deleted successfully!');
    }
}