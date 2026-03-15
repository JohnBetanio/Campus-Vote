<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Voter;
use Inertia\Inertia;

class ResultController extends Controller
{
    public function index()
    {
        $elections = Election::with(['positions.candidates'])->get();
        $stats = [
            'total_voters' => Voter::count(),
            'votes_cast' => 0,
            'participation_rate' => 0,
            'positions' => 0,
        ];

        return Inertia::render('Admin/Results', [
            'elections' => $elections->map(fn ($e) => ['id' => $e->id, 'title' => $e->title])->values()->all(),
            'election' => null,
            'results' => [],
            'stats' => $stats,
        ]);
    }

    public function show(Election $election)
    {
        $elections = Election::with(['positions.candidates'])->get();
        $election->load(['positions.candidates.votes']);

        $totalVoters = Voter::count();
        $votesCount = $election->votes()->distinct('voter_id')->count('voter_id');

        $stats = [
            'total_voters' => $totalVoters,
            'votes_cast' => $votesCount,
            'participation_rate' => $totalVoters > 0 ? round(($votesCount / $totalVoters) * 100) : 0,
            'positions' => $election->positions->count(),
        ];

        $results = [];
        foreach ($election->positions as $position) {
            $candidates = $position->candidates()->withCount('votes')->get();
            $totalVotes = $candidates->sum('votes_count');

            $results[$position->name] = $candidates->map(function ($candidate) use ($totalVotes) {
                return [
                    'name' => $candidate->name,
                    'votes' => $candidate->votes_count,
                    'percentage' => $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100) : 0,
                ];
            })->sortByDesc('votes')->values()->all();
        }

        return Inertia::render('Admin/Results', [
            'elections' => $elections->map(fn ($e) => ['id' => $e->id, 'title' => $e->title])->values()->all(),
            'election' => [
                'id' => $election->id,
                'title' => $election->title,
                'status' => $election->status,
            ],
            'results' => $results,
            'stats' => $stats,
        ]);
    }
}