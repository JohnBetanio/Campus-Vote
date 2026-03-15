<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Voter;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all elections with their data
        $activeElections = Election::where('status', 'active')
            ->with(['positions.candidates.votes', 'votes'])
            ->orderBy('created_at', 'desc')
            ->get();

        $allElections = Election::with(['positions.candidates.votes', 'votes'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate comprehensive statistics
        $totalElections = $allElections->count();
        $activeElectionsCount = $activeElections->count();
        $totalVoters = Voter::count();
        $totalVotesCast = Vote::count();

        // Active election stats
        $totalPositions = 0;
        $totalNominees = 0;
        $totalVotedCount = 0;
        $participationRate = 0;
        $results = [];

        if ($activeElections->count() > 0) {
            foreach ($activeElections as $election) {
                $totalPositions += $election->positions->count();
                $totalNominees += $election->candidates->count();
                $votedCount = Vote::where('election_id', $election->id)
                    ->distinct('voter_id')
                    ->count('voter_id');
                $totalVotedCount += $votedCount;

                // Calculate results for each election
                foreach ($election->positions as $position) {
                    $candidates = $position->candidates()->withCount('votes')->get();
                    $totalVotes = $candidates->sum('votes_count');

                    $results[$election->id][$position->name] = $candidates->map(function ($candidate) use ($totalVotes) {
                        return [
                            'name' => $candidate->name,
                            'votes' => $candidate->votes_count,
                            'percentage' => $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 2) : 0,
                        ];
                    })->sortByDesc('votes');
                }
            }

            if ($totalVoters > 0) {
                $participationRate = round(($totalVotedCount / $totalVoters) * 100, 2);
            }
        }

        $activeElectionsData = $activeElections->map(function ($election) use ($totalVoters) {
            $electionVotedCount = Vote::where('election_id', $election->id)
                ->distinct('voter_id')
                ->count('voter_id');
            $electionParticipationRate = $totalVoters > 0
                ? round(($electionVotedCount / $totalVoters) * 100, 1)
                : 0;

            return [
                'id' => $election->id,
                'title' => $election->title,
                'created_at' => $election->created_at?->toISOString(),
                'votes_count' => $election->votes->count(),
                'participation_rate' => $electionParticipationRate,
                'positions_count' => $election->positions->count(),
                'edit_url' => route('admin.elections.edit', $election),
            ];
        })->values()->all();

        return Inertia::render('Admin/Dashboard', [
            'activeElections' => $activeElectionsData,
            'allElections' => $allElections->map(fn ($e) => $e->toArray())->all(),
            'totalElections' => $totalElections,
            'activeElectionsCount' => $activeElectionsCount,
            'totalVoters' => $totalVoters,
            'totalVotesCast' => $totalVotesCast,
            'totalPositions' => $totalPositions,
            'totalNominees' => $totalNominees,
            'totalVotedCount' => $totalVotedCount,
            'participationRate' => $participationRate,
            'results' => $results,
        ]);
    }
}
