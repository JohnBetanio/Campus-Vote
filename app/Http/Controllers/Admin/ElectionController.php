<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Voter;
use App\Events\ElectionEnded;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::with(['positions.candidates', 'votes'])->orderBy('created_at', 'desc')->get();
        $activeElections = $elections->where('status', 'active');
        $endedElections = $elections->where('status', 'ended');
        $totalVoters = Voter::count();

        $electionsData = $elections->map(function ($election) use ($totalVoters) {
            $electionVotedCount = Vote::where('election_id', $election->id)
                ->distinct('voter_id')
                ->count('voter_id');
            $electionParticipationRate = $totalVoters > 0
                ? round(($electionVotedCount / $totalVoters) * 100, 1)
                : 0;

            return [
                'id' => $election->id,
                'title' => $election->title,
                'status' => $election->status,
                'created_at' => $election->created_at?->toISOString(),
                'positions_count' => $election->positions->count(),
                'candidates_count' => $election->positions->sum(fn ($p) => $p->candidates->count()),
                'votes_cast' => $electionVotedCount,
                'participation_rate' => $electionParticipationRate,
                'positions' => $election->positions->map(fn ($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'candidates' => $p->candidates->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->all(),
                ])->all(),
                'edit_url' => route('admin.elections.edit', $election),
                'end_url' => route('admin.elections.end', $election),
                'destroy_url' => route('admin.elections.destroy', $election),
            ];
        })->values()->all();

        return Inertia::render('Admin/ManageElections', [
            'elections' => $electionsData,
            'activeElections' => $activeElections->values()->map(fn ($e) => ['id' => $e->id])->all(),
            'endedElections' => $endedElections->values()->map(fn ($e) => ['id' => $e->id])->all(),
        ]);
    }

    public function create()
    {
        $activeElection = Election::where('status', 'active')->first();
        return Inertia::render('Admin/CreateElection', [
            'activeElection' => $activeElection ? ['id' => $activeElection->id, 'title' => $activeElection->title] : null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'positions' => 'required|array|min:1',
            'positions.*.name' => 'required|string|max:255',
            'positions.*.candidates' => 'required|array|min:1',
            'positions.*.candidates.*' => 'required|string|max:255',
        ]);

        $election = Election::create([
            'title' => $request->title,
            'status' => 'active',
        ]);

        foreach ($request->positions as $index => $positionData) {
            $position = Position::create([
                'election_id' => $election->id,
                'name' => $positionData['name'],
                'order' => $index,
                'max_votes' => isset($positionData['max_votes']) ? intval($positionData['max_votes']) : 1,
            ]);

            foreach ($positionData['candidates'] as $candidateName) {
                Candidate::create([
                    'position_id' => $position->id,
                    'name' => $candidateName,
                ]);
            }
        }

        return redirect()->route('admin.elections.index')->with('success', 'Election created successfully!');
    }

    public function edit(Election $election)
    {
        $election->load(['positions.candidates']);
        $electionData = [
            'id' => $election->id,
            'title' => $election->title,
            'status' => $election->status,
            'positions' => $election->positions->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'order' => $p->order,
                'max_votes' => $p->max_votes ?? 1,
                'candidates' => $p->candidates->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->all(),
            ])->all(),
        ];

        return Inertia::render('Admin/EditElection', [
            'election' => $electionData,
            'updateUrl' => route('admin.elections.update', $election),
            'cancelUrl' => route('admin.elections.index'),
        ]);
    }

    public function update(Request $request, Election $election)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'positions' => 'required|array|min:1',
            'positions.*.name' => 'required|string|max:255',
            'positions.*.candidates' => 'required|array|min:1',
            'positions.*.candidates.*' => 'required|string|max:255',
        ]);

        $election->update(['title' => $request->title]);

        // Delete existing positions and candidates
        $election->positions()->delete();

        foreach ($request->positions as $index => $positionData) {
            $position = Position::create([
                'election_id' => $election->id,
                'name' => $positionData['name'],
                'order' => $index,
                'max_votes' => isset($positionData['max_votes']) ? intval($positionData['max_votes']) : 1,
            ]);

            foreach ($positionData['candidates'] as $candidateName) {
                Candidate::create([
                    'position_id' => $position->id,
                    'name' => $candidateName,
                ]);
            }
        }

        return redirect()->route('admin.elections.index')->with('success', 'Election updated successfully!');
    }

    public function endElection(Election $election)
    {
        // calculate winners per position before closing the election
        $winners = [];
        foreach ($election->positions()->with('candidates.votes')->get() as $position) {
            // determine candidate with max votes
            $top = $position->candidates->sortByDesc(function ($cand) {
                return $cand->votes->count();
            })->first();

            if ($top) {
                $winners[$position->name] = [
                    'name' => $top->name,
                    'votes' => $top->votes->count(),
                ];
            }
        }

        $election->status = 'ended';
        $election->winners = $winners;
        $election->save();

        // create an announcement for voters
        if (!empty($winners)) {
            $summary = [];
            foreach ($winners as $pos => $info) {
                $summary[] = "{$pos}: {$info['name']} ({$info['votes']} votes)";
            }
            \App\Models\Announcement::create([
                'content' => "Election '{$election->title}' has ended. Winners - " . implode(' | ', $summary)
            ]);
        }

        // Dispatch event to broadcast election results to all connected voters
        ElectionEnded::dispatch($election);

        return redirect()->route('admin.elections.index')->with('success', 'Election ended and winners declared.');
    }

    public function destroy(Election $election)
    {
        $election->delete();
        return redirect()->route('admin.elections.index')->with('success', 'Election deleted successfully!');
    }
}
