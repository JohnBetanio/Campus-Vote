@extends('layouts.dashboard')

@section('title', 'View Results')

@section('page-title', 'Election Results')
@section('page-subtitle', 'View live and past election results.')

@section('content')
    <style>
        .results-selector-banner {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .results-selector-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .results-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .results-tab {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .results-tab:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .results-tab.active {
            background: white;
            color: #1a7a8f;
            border-color: white;
        }

        .results-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 35px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(34, 134, 58, 0.3);
        }

        .results-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .election-title {
            font-size: 18px;
            opacity: 20.9;
            margin: 0;
            color: white;
        }

        .results-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-icon {
            font-size: 20px;
        }

        .info-label {
            font-size: 12px;
            opacity: 0.9;
            text-transform: uppercase;
        }

        .info-value {
            font-size: 16px;
            font-weight: 700;
            display: block;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 12px;
            margin-top: 15px;
        }

        .status-badge.active {
            background: #24a1b8;
            color: white;
        }

        .status-badge.ended {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-left: 10px;
            font-size: 11px;
            font-weight: 700;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse-dot 1.5s infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .position-results-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-top: 4px solid #24a1b8;
        }

        .position-title {
            font-size: 22px;
            font-weight: 700;
            color: #333;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .position-title-icon {
            font-size: 24px;
        }

        .candidate-result {
            margin-bottom: 24px;
        }

        .candidate-result:last-child {
            margin-bottom: 0;
        }

        .candidate-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .candidate-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #1a7a8f, #24a1b8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .candidate-avatar svg {
            width: 26px;
            height: 26px;
            fill: white;
        }

        .candidate-info {
            flex: 1;
        }

        .candidate-name {
            font-weight: 700;
            color: #333;
            font-size: 15px;
        }

        .candidate-stats {
            display: flex;
            gap: 15px;
            margin-top: 2px;
        }

        .vote-stat {
            display: flex;
            gap: 4px;
            align-items: center;
            font-size: 12px;
            color: #666;
        }

        .vote-count {
            font-weight: 700;
            color: #24a1b8;
        }

        .percentage {
            font-weight: 700;
            color: #1a7a8f;
        }

        .progress-container {
            margin-top: 8px;
        }

        .progress-bar-bg {
            background: #e8e8e8;
            height: 28px;
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #24a1b8 0%, #10b981 100%);
            display: flex;
            align-items: center;
            padding: 0 12px;
            transition: width 0.5s ease;
            min-width: 0;
        }

        .progress-bar.first {
            background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
        }

        .progress-bar.second {
            background: linear-gradient(90deg, #a7f3d0 0%, #6ee7b7 100%);
        }

        .progress-bar.third {
            background: linear-gradient(90deg, #bbf7d0 0%, #86efac 100%);
        }

        .progress-percent {
            color: white;
            font-weight: 700;
            font-size: 13px;
            white-space: nowrap;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .empty-state-container {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .empty-state-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .empty-state-text {
            font-size: 16px;
            color: #666;
        }

        .medal {
            font-size: 20px;
            margin-right: 10px;
        }
    </style>

    <!-- Election Selector for Multiple Elections -->
    @if ($elections && count($elections) > 1)
        <div class="results-selector-banner">
            <div class="results-selector-title">📊 Select an Election to View Results:</div>
            <div class="results-tabs">
                @foreach ($elections as $elec)
                    <a href="{{ route('voter.results', ['election_id' => $elec->id]) }}"
                        class="results-tab {{ isset($election) && $elec->id === $election->id ? 'active' : '' }}">
                        {{ $elec->title }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if ($election)
        <!-- Results Header -->
        <div class="results-header">
            <h1 class="results-title">📊 Election Results</h1>
            <p class="election-title">{{ $election->title }}</p>

            <span class="status-badge {{ $election->status === 'active' ? 'active' : 'ended' }}">
                {{ ucfirst($election->status) }}
                @if ($election->status === 'active')
                    <span class="live-indicator">
                        <span class="live-dot"></span>
                        LIVE
                    </span>
                @endif
            </span>

            <div class="results-info-grid">
                <div class="info-item">
                    <span class="info-icon">📅</span>
                    <div>
                        <span class="info-label">Created</span>
                        <span class="info-value">{{ $election->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-icon">🕐</span>
                    <div>
                        <span class="info-label">Last Updated</span>
                        <span class="info-value" id="last-updated">{{ now()->format('g:i A') }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-icon">🗳️</span>
                    <div>
                        <span class="info-label">Total Votes</span>
                        <span class="info-value" id="total-votes"
                            data-initial-votes="{{ $totalVotes ?? 0 }}">{{ $totalVotes ?? 0 }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-icon">📌</span>
                    <div>
                        <span class="info-label">Positions</span>
                        <span class="info-value">{{ $election->positions->count() }}</span>
                    </div>
                </div>
            </div>

            @if ($election->status === 'ended' && !empty($election->winners))
                <div class="position-results-card" style="border-top-color: #24a1b8; margin-top:20px;">
                    <h3 class="position-title" style="color:#1a7a8f;">🏆 Winners Summary</h3>
                    <ul style="margin: 0; padding-left: 20px; color: #333;">
                        @foreach ($election->winners as $pos => $info)
                            <li><strong>{{ $pos }}:</strong> {{ $info['name'] }} ({{ $info['votes'] }} votes)
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @if (count($results) > 0)
            <!-- Results Container -->
            <div id="results-container">
                @foreach ($results as $positionName => $candidates)
                    <div class="position-results-card" data-position="{{ $positionName }}">
                        <h3 class="position-title">
                            <span class="position-title-icon">📌</span>
                            {{ $positionName }}
                        </h3>

                        @foreach ($candidates as $index => $candidate)
                            <div class="candidate-result" data-candidate-id="{{ $candidate['id'] }}">
                                <div class="candidate-header">
                                    <div class="candidate-avatar">
                                        <svg viewBox="0 0 24 24">
                                            <path
                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </div>
                                    <div class="candidate-info">
                                        <div class="candidate-name">
                                            @if ($index == 0)
                                                <span class="medal">🥇</span>
                                            @elseif($index == 1)
                                                <span class="medal">🥈</span>
                                            @elseif($index == 2)
                                                <span class="medal">🥉</span>
                                            @endif
                                            {{ $candidate['name'] }}
                                        </div>
                                        <div class="candidate-stats">
                                            <div class="vote-stat">
                                                <span class="vote-count">{{ $candidate['votes'] }}</span> votes
                                            </div>
                                            <div class="vote-stat">
                                                <span class="percentage">{{ $candidate['percentage'] }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress-container">
                                    <div class="progress-bar-bg">
                                        <div class="progress-bar {{ $index == 0 ? 'first' : ($index == 1 ? 'second' : 'third') }}"
                                            style="width: {{ $candidate['percentage'] }}%">
                                            @if ($candidate['percentage'] > 15)
                                                <span class="progress-percent">{{ $candidate['percentage'] }}%</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @elseif(isset($resultsLocked) && $resultsLocked && $election->isActive())
            <!-- Election is Active - Results Locked -->
            <div class="empty-state-container">
                <div class="empty-state-icon">🔒</div>
                <div class="empty-state-title">Results Not Available Yet</div>
                <p class="empty-state-text">
                    The election is currently active. Vote counts and results are hidden to maintain election integrity.
                    <br><br>
                    <strong>Results will be available after the election ends.</strong>
                </p>
            </div>
        @else
            <div class="empty-state-container">
                <div class="empty-state-icon">📊</div>
                <div class="empty-state-title">No Results Yet</div>
                <p class="empty-state-text">Results will appear here once voting begins in this election.</p>
            </div>
        @endif

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const totalVotesElement = document.getElementById('total-votes');
                    let pollInterval = null;

                    // Start live polling for results
                    startLivePolling();

                    function startLivePolling() {
                        // Check for updates every 3 seconds
                        if (pollInterval) clearInterval(pollInterval);
                        pollInterval = setInterval(() => {
                            fetchLatestResults();
                        }, 3000);
                    }

                    function fetchLatestResults() {
                        const electionId = {{ $election->id }};
                        fetch(`/voter/api/election/${electionId}/results`)
                            .then(response => {
                                // Handle 403 Forbidden - election is active
                                if (response.status === 403) {
                                    console.log('Results not available - election is active');
                                    if (pollInterval) clearInterval(pollInterval);
                                    return null;
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data && data.results) {
                                    updateResults(data);
                                    updateLastUpdated();
                                }
                            })
                            .catch(err => console.log('Results fetch failed:', err));
                    }

                    function updateResults(data) {
                        if (!data.results) return;

                        const container = document.getElementById('results-container');
                        if (!container) return;

                        let totalVotes = 0;

                        for (const [positionName, candidates] of Object.entries(data.results)) {
                            const positionDiv = container.querySelector(`[data-position="${positionName}"]`);
                            if (!positionDiv) continue;

                            candidates.forEach((candidate, index) => {
                                totalVotes += candidate.votes;

                                const candidateDiv = positionDiv.querySelector(
                                    `[data-candidate-id="${candidate.id}"]`);
                                if (candidateDiv) {
                                    const voteCountEl = candidateDiv.querySelector('.vote-count');
                                    const percentageEl = candidateDiv.querySelector('.percentage');

                                    if (voteCountEl && voteCountEl.textContent !== String(candidate.votes)) {
                                        voteCountEl.style.transition = 'all 0.4s ease';
                                        voteCountEl.style.transform = 'scale(1.2)';
                                        voteCountEl.textContent = candidate.votes;

                                        setTimeout(() => {
                                            voteCountEl.style.transform = 'scale(1)';
                                        }, 50);
                                    }

                                    if (percentageEl) {
                                        percentageEl.textContent = candidate.percentage;
                                    }

                                    const progressBar = candidateDiv.querySelector('.progress-bar');
                                    if (progressBar) {
                                        progressBar.style.width = candidate.percentage + '%';
                                    }
                                }
                            });
                        }

                        updateTotalVotesDisplay(totalVotes);
                    }

                    function updateTotalVotesDisplay(totalVotes) {
                        if (totalVotesElement) {
                            const currentValue = parseInt(totalVotesElement.textContent) || 0;

                            if (currentValue !== totalVotes) {
                                totalVotesElement.style.transition = 'all 0.3s ease';
                                totalVotesElement.style.transform = 'scale(1.15)';
                                totalVotesElement.style.color = '#24a1b8';

                                totalVotesElement.textContent = totalVotes;
                                setTimeout(() => {
                                    totalVotesElement.style.transform = 'scale(1)';
                                    totalVotesElement.style.color = 'white';
                                }, 50);
                            }
                        }
                    }

                    function updateLastUpdated() {
                        const element = document.getElementById('last-updated');
                        if (element) {
                            const now = new Date();
                            element.textContent = now.toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                        }
                    }

                    window.addEventListener('beforeunload', function() {
                        if (pollInterval) clearInterval(pollInterval);
                    });
                });
            </script>
        @endpush
    @else
        <div class="empty-state-container">
            <div class="empty-state-icon">❌</div>
            <div class="empty-state-title">Election Not Found</div>
            <p class="empty-state-text">The election you're looking for doesn't exist or is not available.</p>
        </div>
    @endif

@endsection
