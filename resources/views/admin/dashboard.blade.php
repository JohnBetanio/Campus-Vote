@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Monitor elections, voters, and participation in real-time.')

@section('page-actions')
    <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">+ Add Election</a>
    <a href="{{ route('admin.elections.index') }}" class="btn btn-secondary">Manage Elections</a>
@endsection

@section('content')
    <style>
        .quick-action-btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 8px 0;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);
        }

        .quick-action-btn:hover {
            background: linear-gradient(135deg, #059669, #10b981);
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.4);
            transform: translateY(-1px);
        }

        .result-chart {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }
    </style>

    <div class="dashboard-content" style="max-width: 1280px; margin: 0 auto;">
        {{-- KPI Cards: responsive grid, icons, clickable, accessibility --}}
        <section class="kpi-grid" aria-label="Key metrics">
            <a href="{{ route('admin.elections.index') }}" class="kpi-card kpi-card--primary"
                title="View all elections ({{ $activeElectionsCount }} active)"
                aria-label="Total elections: {{ $totalElections }}, {{ $activeElectionsCount }} active. Go to manage elections.">
                <div class="kpi-card__icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                    </svg>
                </div>
                <div class="kpi-card__value">{{ $totalElections }}</div>
                <div class="kpi-card__label">Total Elections</div>
                <div class="kpi-card__detail">{{ $activeElectionsCount }} active •
                    {{ max(0, $totalElections - $activeElectionsCount) }} ended</div>
            </a>

            <a href="{{ route('admin.voters.index') }}" class="kpi-card" title="View registered voters"
                aria-label="Registered voters: {{ $totalVoters }}, {{ $totalVotedCount }} {{ $totalVotedCount === 1 ? 'has' : 'have' }} participated.">
                <div class="kpi-card__icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <div class="kpi-card__value">{{ $totalVoters }}</div>
                <div class="kpi-card__label">Registered Voters</div>
                <div class="kpi-card__detail">{{ $totalVotedCount }} {{ $totalVotedCount === 1 ? 'has' : 'have' }}
                    participated</div>
            </a>

            <a href="{{ route('admin.results.index') }}" class="kpi-card" title="View participation analytics"
                aria-label="Participation rate: {{ $participationRate }}%. {{ $totalVotesCast }} ballots cast.">
                <div class="kpi-card__icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                    </svg>
                </div>
                <div class="kpi-card__value">{{ $participationRate }}%</div>
                <div class="kpi-card__label">Participation Rate</div>
                <div class="kpi-card__detail">{{ $totalVotesCast }} {{ $totalVotesCast === 1 ? 'ballot' : 'ballots' }}
                    cast</div>
                <div class="kpi-card__progress" role="presentation">
                    <div class="kpi-card__progress-fill" style="width: {{ min(100, $participationRate) }}%"></div>
                </div>
            </a>

            <a href="{{ route('admin.elections.index') }}" class="kpi-card"
                title="Positions and candidates in active elections"
                aria-label="Positions: {{ $totalPositions }}, candidates: {{ $totalNominees }}.">
                <div class="kpi-card__icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                </div>
                <div class="kpi-card__value">{{ $totalPositions }}/{{ $totalNominees }}</div>
                <div class="kpi-card__label">Positions / Candidates</div>
                <div class="kpi-card__detail">Across active elections</div>
            </a>
        </section>

        {{-- Quick Actions --}}
        <section class="card" style="margin-bottom: 30px;" aria-labelledby="quick-actions-heading">
            <h2 id="quick-actions-heading" style="margin-bottom: 20px; color: var(--primary); font-size: 1.25rem;">Quick
                actions</h2>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('admin.elections.create') }}" class="quick-action-btn">Create election</a>
                <a href="{{ route('admin.elections.index') }}" class="quick-action-btn">Manage elections</a>
                <a href="{{ route('admin.voters.index') }}" class="quick-action-btn">Manage voters</a>
                <a href="{{ route('admin.announcements.index') }}" class="quick-action-btn">Announcements</a>
            </div>
    </div>

    {{-- Active Elections --}}
    @if ($activeElections->count() > 0)
        <section class="card" aria-labelledby="active-elections-heading">
            <h2 id="active-elections-heading" class="card-title">Active elections – live overview</h2>

            @foreach ($activeElections as $election)
                <div
                    style="margin-bottom: 40px; padding: 20px; background: #f9fafb; border-radius: 12px; border-left: 4px solid #059669;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div>
                            <h3 style="font-size: 20px; margin: 0; color: #047857;">{{ $election->title }}</h3>
                            <p style="margin: 5px 0; color: #6b7280; font-size: 14px;">
                                Created {{ $election->created_at->format('M d, Y g:i A') }}
                            </p>
                        </div>
                        <a href="{{ route('admin.elections.edit', $election) }}" class="btn btn-secondary"
                            style="padding: 8px 16px;">Edit election</a>
                    </div>

                    @php
                        $electionVotedCount = \App\Models\Vote::where('election_id', $election->id)
                            ->distinct('voter_id')
                            ->count('voter_id');
                        $electionParticipationRate =
                            $totalVoters > 0 ? round(($electionVotedCount / $totalVoters) * 100, 1) : 0;
                    @endphp

                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                        <div style="background: white; padding: 15px; border-radius: 8px;">
                            <p
                                style="margin: 0; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                Votes cast</p>
                            <p style="margin: 8px 0 0; font-size: 24px; font-weight: 700; color: #047857;">
                                {{ $election->votes->count() }}</p>
                        </div>
                        <div style="background: white; padding: 15px; border-radius: 8px;">
                            <p
                                style="margin: 0; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                Participation</p>
                            <p style="margin: 8px 0 0; font-size: 24px; font-weight: 700; color: #059669;">
                                {{ $electionParticipationRate }}%</p>
                        </div>
                        <div style="background: white; padding: 15px; border-radius: 8px;">
                            <p
                                style="margin: 0; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                Positions</p>
                            <p style="margin: 8px 0 0; font-size: 24px; font-weight: 700; color: #047857;">
                                {{ $election->positions->count() }}</p>
                        </div>
                    </div>

                    <!-- Results by Position -->
                    @if (isset($results[$election->id]))
                        @foreach ($results[$election->id] as $positionName => $candidates)
                            <div class="result-chart">
                                <h4 style="margin-top: 0; color: #0f172a;">{{ $positionName }}</h4>

                                @foreach ($candidates as $index => $candidate)
                                    <div style="margin-bottom: 15px;">
                                        <div
                                            style="display: flex; justify-content: space-between; margin-bottom: 6px; align-items: center;">
                                            <div style="flex: 1;">
                                                <span style="font-weight: 600; color: #111827;">
                                                    {{ $index + 1 }}. {{ $candidate['name'] }}
                                                </span>
                                            </div>
                                            <span
                                                style="background: #059669; color: white; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600;">
                                                {{ $candidate['votes'] }} votes ({{ $candidate['percentage'] }}%)
                                            </span>
                                        </div>
                                        <div
                                            style="background: #e5e7eb; height: 20px; border-radius: 999px; overflow: hidden;">
                                            <div class="progress-bar"
                                                style="width: {{ $candidate['percentage'] }}%; background: linear-gradient(90deg, #047857, #10b981); height: 100%; display: flex; align-items: center; padding: 0 10px;">
                                                @if ($candidate['percentage'] > 10)
                                                    <span
                                                        style="color: #ecfdf3; font-weight: 600; font-size: 12px;">{{ $candidate['percentage'] }}%</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </section>
    @else
        <section class="card" aria-label="No active elections">
            <div class="empty-state">
                <p class="empty-state-title">No active elections</p>
                <p class="empty-state-text">Create a new election to begin collecting votes.</p>
                <a href="{{ route('admin.elections.create') }}" class="btn btn-success">Create election</a>
            </div>
        </section>
    @endif
    </div>
@endsection
