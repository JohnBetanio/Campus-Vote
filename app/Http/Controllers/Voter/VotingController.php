<?php

namespace App\Http\Controllers\Voter;

use App\Models\Vote;
use App\Models\Voter;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Announcement;
use App\Models\ElectionVoter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class VotingController extends Controller
{


    /**
     * Show the voter dashboard.
     */
    public function dashboard()
    {
        $voter = Auth::guard('voter')->user();
        $announcements = Announcement::orderBy('created_at', 'desc')->limit(4)->get();
        $activeElections = Election::where('status', 'active')->with(['positions', 'candidates'])->get();
        // recently ended elections with winners for dashboard notices
        $recentWinners = Election::where('status', 'ended')
            ->whereNotNull('winners')
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();

        $voterData = $voter ? [
            'id' => $voter->id,
            'name' => $voter->name,
            'email' => $voter->email,
            'course' => $voter->course,
        ] : null;

        $announcementsData = $announcements->map(fn($a) => [
            'id' => $a->id,
            'content' => $a->content,
            'created_at' => $a->created_at?->toISOString(),
        ])->all();

        $activeElectionsData = $activeElections->map(function ($election) use ($voter) {
            $hasVoted = $voter
                ? Vote::where('voter_id', $voter->id)->where('election_id', $election->id)->exists()
                : false;

            return [
                'id' => $election->id,
                'title' => $election->title,
                'created_at' => $election->created_at?->toISOString(),
                'positions_count' => $election->positions->count(),
                'candidates_count' => $election->candidates->count(),
                'has_voted' => $hasVoted,
            ];
        })->all();

        $recentWinnersData = $recentWinners->map(fn($e) => [
            'id' => $e->id,
            'title' => $e->title,
            'winners' => $e->winners,
        ])->all();

        return Inertia::render('Voter/Dashboard', [
            'voter' => $voterData,
            'announcements' => $announcementsData,
            'activeElections' => $activeElectionsData,
            'recentWinners' => $recentWinnersData,
        ]);
    }

    /**
     * Show the voting page.
     */
    public function vote(Request $request)
    {
        $voter = Auth::guard('voter')->user();
        $electionId = $request->query('election_id');

        if ($electionId) {
            $election = Election::where('id', $electionId)
                ->where('status', 'active')
                ->with(['positions.candidates'])
                ->first();

            if (!$election) {
                return redirect()->route('voter.dashboard')->with('error', 'Election not found or is not active.');
            }
        } else {
            $election = Election::where('status', 'active')->with(['positions.candidates'])->first();

            if (!$election) {
                return redirect()->route('voter.dashboard')->with('error', 'No active elections at the moment.');
            }
        }

        // Check if voter has already voted in this election
        $hasVoted = Vote::where('voter_id', $voter->id)
            ->where('election_id', $election->id)
            ->exists();

        if ($hasVoted) {
            return redirect()->route('voter.votes')->with('info', 'You have already voted in this election. View your vote history.');
        }

        // Get all active elections for navigation
        $activeElections = Election::where('status', 'active')->get();

        $electionData = [
            'id' => $election->id,
            'title' => $election->title,
            'created_at' => $election->created_at?->toISOString(),
            'positions' => $election->positions->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'max_votes' => $p->max_votes ?? 1,
                'candidates' => $p->candidates->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->all(),
            ])->values()->all(),
        ];
        $activeElectionsData = $activeElections->map(fn($e) => [
            'id' => $e->id,
            'title' => $e->title,
        ])->values()->all();

        return Inertia::render('Voter/Vote', [
            'election' => $electionData,
            'activeElections' => $activeElectionsData,
        ]);
    }

    /**
     * Submit the vote.
     */
    public function submitVote(Request $request)
    {
        $request->validate([
            'election_id' => 'required|integer|exists:elections,id',
        ]);

        $voter = Auth::guard('voter')->user();
        $electionId = $request->input('election_id');

        $election = Election::where('id', $electionId)
            ->where('status', 'active')
            ->with(['positions.candidates'])
            ->first();

        if (!$election) {
            return redirect()
                ->route('voter.dashboard')
                ->with('error', 'Election not found or is not active.');
        }

        // Check if already voted (prevent duplicate) - application-level guard
        $hasVoted = Vote::where('voter_id', $voter->id)
            ->where('election_id', $election->id)
            ->exists();

        if ($hasVoted) {
            return redirect()
                ->route('voter.votes')
                ->with('error', 'You have already voted in this election.');
        }

        // Allow selecting multiple candidates per position (e.g., 2 selections)
        $requiredPerPosition = 2; // change this number to adjust number of allowed selections per position

        // Basic validation structure: votes must be an array of arrays
        $request->validate([
            'votes' => 'required|array',
            'votes.*' => 'array',
            'votes.*.*' => 'required|integer|exists:candidates,id',
        ]);

        $positionIds = $election->positions->pluck('id')->toArray();

        // Ensure each position has a selection and no duplicates
        foreach ($positionIds as $positionId) {
            $selected = $request->input('votes.' . $positionId, []);
            if (!is_array($selected) || count($selected) === 0) {
                return back()->with('error', 'Please select candidate(s) for all positions.');
            }

            // Check for duplicate candidate selections in the same position
            if (count($selected) !== count(array_unique($selected))) {
                return back()->with('error', 'Duplicate candidate selections are not allowed for the same position.');
            }
        }

        DB::beginTransaction();
        try {
            // Database-level guard: record one ballot per voter per election
            ElectionVoter::create([
                'voter_id' => $voter->id,
                'election_id' => $election->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            foreach ($election->positions as $position) {
                $positionId = $position->id;
                $available = $position->candidates->count();
                $maxVotes = isset($position->max_votes) ? intval($position->max_votes) : $requiredPerPosition;
                $allowed = min($maxVotes, $available);
                $selected = $request->input('votes.' . $positionId, []);

                if (!is_array($selected)) {
                    throw new \Exception('Invalid selection for a position.');
                }

                // If the position has enough candidates, require exactly maxVotes selections
                if ($available >= $maxVotes && count($selected) != $maxVotes) {
                    throw new \Exception("Position '{$position->name}' requires selecting {$maxVotes} candidates.");
                }

                // Otherwise require at least 1 and at most allowed
                if ($available < $maxVotes && (count($selected) < 1 || count($selected) > $allowed)) {
                    throw new \Exception("Position '{$position->name}' requires selecting between 1 and {$allowed} candidates.");
                }

                // Create vote entries for each selected candidate
                foreach ($selected as $candidateId) {
                    $candidate = Candidate::where('id', $candidateId)
                        ->where('position_id', $positionId)
                        ->first();

                    if (!$candidate) {
                        throw new \Exception('Invalid candidate for position: ' . $position->name);
                    }

                    Vote::create([
                        'voter_id' => $voter->id,
                        'candidate_id' => $candidateId,
                        'election_id' => $election->id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }
            }

            DB::commit();

            Log::info('Votes submitted', [
                'voter_id' => $voter->id,
                'election_id' => $election->id,
                'ip' => $request->ip(),
            ]);

            return redirect()
                ->route('voter.voting.confirmation', ['election_id' => $election->id])
                ->with('success', 'Your vote has been submitted successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // Handle unique constraint violation on election_voters gracefully
            if ($e->getCode() === '23000') {
                // Check if it's a duplicate vote attempt
                $hasVoted = Vote::where('voter_id', $voter->id)
                    ->where('election_id', $election->id)
                    ->exists();

                if ($hasVoted) {
                    return redirect()
                        ->route('voter.votes')
                        ->with('error', 'You have already voted in this election.');
                } else {
                    return redirect()
                        ->route('voter.votes')
                        ->with('error', 'Your ballot has already been recorded for this election.');
                }
            }

            Log::error('Vote submission database error', [
                'voter_id' => $voter->id,
                'election_id' => $election->id,
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
            ]);

            return back()->with('error', 'A database error occurred while submitting your vote. Please try again.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Vote submission error', [
                'voter_id' => $voter->id,
                'election_id' => $election->id,
                'error_message' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred while submitting your vote. Please try again.');
        }
    }

    /**
     * Show voter's submitted votes.
     */
    public function showVotes()
    {
        $voter = Auth::guard('voter')->user();
        $elections = Election::with(['votes' => function ($query) use ($voter) {
            $query->where('voter_id', $voter->id);
        }])->get();

        // Get votes mapped by election (serializable for Inertia)
        $votesByElection = [];
        foreach ($elections as $election) {
            if ($election->votes->count() > 0) {
                $votes = Vote::where('voter_id', $voter->id)
                    ->where('election_id', $election->id)
                    ->with(['candidate.position'])
                    ->get()
                    ->groupBy('candidate.position.name');
                $votesArray = [];
                foreach ($votes as $positionName => $voteModels) {
                    $votesArray[$positionName] = $voteModels->map(fn($v) => [
                        'candidate_name' => $v->candidate?->name ?? 'Unknown',
                        'created_at' => $v->created_at?->toISOString(),
                    ])->values()->all();
                }
                $votesCount = collect($votesArray)->flatten(1)->count();
                $positionsCount = count($votesArray);
                $votesByElection[] = [
                    'election' => [
                        'id' => $election->id,
                        'title' => $election->title,
                        'created_at' => $election->created_at?->toISOString(),
                    ],
                    'votes' => $votesArray,
                    'positions_count' => $positionsCount,
                    'votes_count' => $votesCount,
                ];
            }
        }

        return Inertia::render('Voter/Votes', [
            'votesByElection' => array_values($votesByElection),
        ]);
    }

    /**
     * Show election results.
     * Block students from viewing results during active elections.
     * Allow viewing after election ends.
     */
    public function results(Request $request)
    {
        $electionId = $request->query('election_id');

        if ($electionId) {
            $election = Election::where('id', $electionId)
                ->with(['positions.candidates.votes'])
                ->first();
        } else {
            // Get the most recent election (active or ended)
            $election = Election::orderBy('id', 'desc')->with(['positions.candidates.votes'])->first();
        }

        if (!$election) {
            return Inertia::render('Voter/Results', [
                'election' => null,
                'results' => [],
                'totalVotes' => 0,
                'elections' => [],
                'resultsLocked' => false,
            ]);
        }

        // SECURITY CHECK: Block students from seeing vote counts during active election
        if ($election->isActive()) {
            $electionsList = Election::orderBy('created_at', 'desc')->get();
            return Inertia::render('Voter/Results', [
                'election' => [
                    'id' => $election->id,
                    'title' => $election->title,
                ],
                'results' => [],
                'totalVotes' => 0,
                'elections' => $electionsList->map(fn($e) => ['id' => $e->id, 'title' => $e->title])->values()->all(),
                'resultsLocked' => true,
            ]);
        }

        $results = [];
        foreach ($election->positions as $position) {
            $candidates = $position->candidates()->withCount('votes')->get();
            $totalVotesPos = $candidates->sum('votes_count');

            $results[$position->name] = $candidates->map(function ($candidate) use ($totalVotesPos) {
                return [
                    'id' => $candidate->id,
                    'name' => $candidate->name,
                    'votes' => $candidate->votes_count,
                    'percentage' => $totalVotesPos > 0 ? round(($candidate->votes_count / $totalVotesPos) * 100, 2) : 0,
                ];
            })->sortByDesc('votes')->values()->all();
        }

        // Compute total ballots cast (distinct voters) for this election
        $totalVotes = Vote::where('election_id', $election->id)->distinct('voter_id')->count('voter_id');

        $electionsList = Election::orderBy('created_at', 'desc')->get();

        return Inertia::render('Voter/Results', [
            'election' => [
                'id' => $election->id,
                'title' => $election->title,
            ],
            'results' => $results,
            'totalVotes' => $totalVotes,
            'elections' => $electionsList->map(fn($e) => ['id' => $e->id, 'title' => $e->title])->values()->all(),
            'resultsLocked' => false,
        ]);
    }

    /**
     * Show voting confirmation page.
     * Displayed after student submits their vote during active election.
     */
    public function votingConfirmation(Request $request)
    {
        $electionId = $request->query('election_id');

        if (!$electionId) {
            return redirect()->route('voter.dashboard')->with('error', 'Invalid election.');
        }

        $election = Election::findOrFail($electionId);

        return Inertia::render('Voter/VotingConfirmation', [
            'election' => [
                'id' => $election->id,
                'title' => $election->title,
            ],
        ]);
    }

    /**
     * Show voter profile.
     */
    public function profile()
    {
        $voter = Auth::guard('voter')->user();
        $voterData = [
            'id' => $voter->id,
            'name' => $voter->name,
            'email' => $voter->email,
            'course' => $voter->course,
            'campus_email' => $voter->campus_email ?? null,
            'avatar' => $voter->avatar ?? null,
        ];
        return Inertia::render('Voter/Profile', ['voter' => $voterData]);
    }

    /**
     * Show edit form for voter profile.
     */
    public function editProfile()
    {
        $voter = Auth::guard('voter')->user();
        $voterData = [
            'id' => $voter->id,
            'name' => $voter->name,
            'email' => $voter->email,
            'course' => $voter->course,
        ];
        return Inertia::render('Voter/EditProfile', ['voter' => $voterData]);
    }

    /**
     * Update voter profile.
     */
    public function updateProfile(Request $request)
    {
        $voter = Voter::findOrFail(Auth::guard('voter')->user()->id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:voters,email,' . $voter->id,
            'course' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $voter->name = $request->input('name');
        $voter->email = $request->input('email');
        $voter->course = $request->input('course');

        if ($request->filled('password')) {
            $voter->password = Hash::make($request->input('password'));
        }

        $voter->save();

        return redirect()->route('voter.profile')->with('success', 'Profile updated successfully.');
    }
}
