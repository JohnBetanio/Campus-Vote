@extends('layouts.dashboard')

@section('title', 'Voter Dashboard')

@section('page-title', 'Welcome')
@section('page-subtitle', 'Cast your vote and track election results.')

@section('page-actions')
    <a href="{{ route('voter.vote') }}" class="btn btn-primary">🗳️ Vote Now</a>
    <a href="{{ route('voter.results') }}" class="btn btn-secondary">View Results</a>
@endsection

@section('content')
    <style>
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 40px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.25);
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -45%;
            right: -5%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.18) 0%, transparent 70%);
            border-radius: 50%;
        }

        .welcome-title {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
        }

        .welcome-subtitle {
            font-size: 16px;
            opacity: 0.95;
            margin: 0;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.95);
            position: relative;
            z-index: 1;
        }

        .election-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary);
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(148, 163, 184, 0.35);
        }

        .election-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.14);
            border-color: rgba(34, 197, 94, 0.4);
        }

        .election-card-header {
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa, #eef2f7);
            border-bottom: 1px solid #eee;
        }

        .election-card-title {
            font-size: 20px;
            font-weight: 700;
            color: #064e3b;
            margin: 0 0 8px 0;
        }

        .election-card-date {
            font-size: 12px;
            color: #999;
            margin: 0;
        }

        .election-card-body {
            padding: 20px;
            flex: 1;
        }

        .election-stat {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .election-stat-label {
            color: #6b7280;
            font-weight: 600;
        }

        .election-stat-value {
            color: #047857;
            font-weight: 700;
        }

        .election-status {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 10px;
        }

        .election-card-footer {
            padding: 15px 20px;
            background: #f9f9f9;
            border-top: 1px solid #eee;
        }

        .vote-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #22863a, #2ecc71);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .vote-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(34, 134, 58, 0.3);
        }

        .voted-badge {
            width: 100%;
            padding: 12px;
            background: #e8e8e8;
            color: #666;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            cursor: not-allowed;
            font-size: 14px;
        }

        .section-header {
            font-size: 22px;
            font-weight: 700;
            color: #064e3b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .elections-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .announcements-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .announcement-item {
            padding: 15px;
            background: var(--lighter);
            border-left: 4px solid var(--primary);
            border-radius: 4px;
            margin-bottom: 12px;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }

        .announcement-item:last-child {
            margin-bottom: 0;
        }

        .empty-state-banner {
            background: #f9fafb;
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            color: #6b7280;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .empty-state-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .empty-state-text {
            font-size: 14px;
            opacity: 0.8;
        }
    </style>

    <div class="dashboard-content" style="max-width: 1280px; margin: 0 auto;">
        {{-- Welcome Banner --}}
        <section class="welcome-banner" aria-labelledby="welcome-heading">
            <h1 id="welcome-heading" class="welcome-title">Welcome, {{ $voter->name }}</h1>
            <p class="welcome-subtitle">Your secure voting dashboard for all campus elections.</p>
        </section>

        {{-- Quick action cards: consistent with Admin KPI style --}}
        <section class="kpi-grid kpi-grid--actions" aria-label="Quick actions">
            <a href="{{ route('voter.votes') }}" class="kpi-card kpi-card--action"
                title="View your vote history"
                aria-label="Vote History: Review your submitted votes across past elections.">
                <div class="kpi-card__icon" aria-hidden="true">VH</div>
                <div class="kpi-card__value">Vote History</div>
                <div class="kpi-card__detail">Review all your submitted votes across past campus elections</div>
                <span class="kpi-card__cta">Open history</span>
            </a>
            <a href="{{ route('voter.results') }}" class="kpi-card kpi-card--action"
                title="View election results"
                aria-label="Election Results: See current and past results with live updates.">
                <div class="kpi-card__icon" aria-hidden="true">ER</div>
                <div class="kpi-card__value">Election Results</div>
                <div class="kpi-card__detail">See current and past results with live updates when voting is active</div>
                <span class="kpi-card__cta">View results</span>
            </a>
            <a href="{{ route('voter.profile') }}" class="kpi-card kpi-card--action"
                title="Manage your profile"
                aria-label="My Profile: Keep your voter information up to date.">
                <div class="kpi-card__icon" aria-hidden="true">PR</div>
                <div class="kpi-card__value">My Profile</div>
                <div class="kpi-card__detail">Keep your voter information up to date and secure</div>
                <span class="kpi-card__cta">Manage profile</span>
            </a>
        </section>

        <!-- Recent Winners Announcement -->
        @if (isset($recentWinners) && $recentWinners->count() > 0)
            <div class="card" style="margin-bottom:30px; border-left:5px solid #059669;">
                <h3 style="margin:0 0 10px 0; color:#047857;">Recent Election Winners</h3>
                <ul style="margin:0; padding-left:20px; color:#334155;">
                    @foreach ($recentWinners as $rw)
                        @if ($rw->winners && is_array($rw->winners))
                            <li><strong>{{ $rw->title }}:</strong>
                                @foreach ($rw->winners as $pos => $info)
                                    {{ $pos }} – {{ $info['name'] }} ({{ $info['votes'] }}),
                                @endforeach
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Active Elections --}}
        <section aria-labelledby="active-elections-heading">
            <h2 id="active-elections-heading" class="section-header">Active elections</h2>
        @if ($activeElections->count() > 0)
            <div class="elections-grid">
                @foreach ($activeElections as $election)
                    @php
                        $hasVoted = \App\Models\Vote::where('voter_id', $voter->id)
                            ->where('election_id', $election->id)
                            ->exists();
                        // SECURITY: Do not show vote count during active elections
                        $voteCount = $election->isActive()
                            ? null
                            : \App\Models\Vote::where('election_id', $election->id)
                                ->distinct('voter_id')
                                ->count('voter_id');
                    @endphp

                    <div class="election-card">
                        <div class="election-card-header">
                            <h3 class="election-card-title">{{ $election->title }}</h3>
                            <p class="election-card-date">📅 Created {{ $election->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="election-card-body">
                            <div class="election-stat">
                                <span class="election-stat-label">📌 Positions:</span>
                                <span class="election-stat-value">{{ $election->positions->count() }}</span>
                            </div>
                            <div class="election-stat">
                                <span class="election-stat-label">👥 Candidates:</span>
                                <span class="election-stat-value">{{ $election->candidates->count() }}</span>
                            </div>
                            @if ($election->isActive())
                                <div class="election-stat">
                                    <span class="election-stat-label">🗳️ Votes Cast:</span>
                                    <span class="election-stat-value" style="color: #999;">Hidden</span>
                                </div>
                            @else
                                <div class="election-stat">
                                    <span class="election-stat-label">🗳️ Votes Cast:</span>
                                    <span class="election-stat-value">{{ $voteCount }}</span>
                                </div>
                            @endif

                            @if (!$hasVoted)
                                <span class="election-status">READY TO VOTE</span>
                            @else
                                <span class="election-status">✓ VOTED</span>
                            @endif
                        </div>

                        <div class="election-card-footer">
                            @if (!$hasVoted)
                                <a href="{{ route('voter.vote', ['election_id' => $election->id]) }}"
                                    style="text-decoration: none;">
                                    <button class="vote-btn">🗳️ Vote Now</button>
                                </a>
                            @else
                                <button class="voted-badge">✓ You Voted</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state-banner">
                <div class="empty-state-icon">—</div>
                <div class="empty-state-title">No active elections</div>
                <p class="empty-state-text">There are currently no open elections. Please check back later.</p>
            </div>
        @endif
        </section>

        {{-- Announcements --}}
        <section aria-labelledby="announcements-heading">
            <h2 id="announcements-heading" class="section-header">Latest announcements</h2>
            <div class="announcements-section">
            @forelse($announcements as $announcement)
                <div class="announcement-item">
                    <strong>{{ $announcement->created_at->format('M d, Y g:i A') }}:</strong>
                    {{ $announcement->content }}
                </div>
            @empty
                <p style="text-align: center; color: #9ca3af; padding: 20px;">No announcements at this time.</p>
            @endforelse
            </div>
        </section>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Auto-refresh every 10 seconds to check for new elections
                    setInterval(() => {
                        fetch('{{ route('voter.api.status') }}')
                            .then(response => response.json())
                            .then(data => {
                                if (!data.has_active_elections) {
                                    // Optional: reload if no active elections
                                    // location.reload();
                                }
                            })
                            .catch(err => console.log('Status check failed:', err));
                    }, 10000);
                });
            </script>
        @endpush
    </div>
@endsection
