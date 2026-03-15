@extends('layouts.dashboard')

@section('title', 'View Votes')

@section('page-title', 'Your Vote History')
@section('page-subtitle', 'Review all your submitted votes across past elections.')

@section('content')
    <style>
        .votes-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 35px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .votes-header-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .votes-header-subtitle {
            font-size: 14px;
            opacity: 0.95;
            color: white;
            margin: 0;
        }

        .election-votes-group {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 5px solid var(--primary);
        }

        .election-title-header {
            font-size: 22px;
            font-weight: 700;
            color: #333;
            margin: 0 0 5px 0;
        }

        .election-date-info {
            font-size: 12px;
            color: #999;
            margin-bottom: 20px;
        }

        .votes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .vote-card {
            background: linear-gradient(135deg, #f8f9fa, #eef2f7);
            border-radius: 10px;
            padding: 20px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .vote-card:hover {
            border-color: #24a1b8;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15);
            transform: translateY(-2px);
        }

        .position-name {
            font-size: 12px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .candidate-voted {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .candidate-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #1a7a8f, #24a1b8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .candidate-avatar svg {
            width: 24px;
            height: 24px;
            fill: white;
        }

        .candidate-name {
            font-weight: 700;
            color: #333;
            font-size: 14px;
        }

        .vote-timestamp {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 11px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e0e0e0;
        }

        .vote-badge {
            display: inline-block;
            background: linear-gradient(135deg, #1a7a8f, #24a1b8);
            color: white;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 10px;
        }

        .votes-list-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .votes-list-table thead {
            background: #f8f9fa;
        }

        .votes-list-table th {
            padding: 12px;
            text-align: left;
            font-weight: 700;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
            font-size: 13px;
        }

        .votes-list-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #e8e8e8;
        }

        .votes-list-table tbody tr:hover {
            background: #f9f9f9;
        }

        .position-td {
            font-weight: 600;
            color: #24a1b8;
        }

        .candidate-td {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-badge {
            display: inline-block;
            background: #24a1b8;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
        }

        .empty-state-container {
            text-align: center;
            padding: 60px 20px;
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
            margin-bottom: 30px;
        }

        .back-btn {
            padding: 12px 24px;
            background: #24a1b8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .back-btn:hover {
            background: #1a7a8f;
            box-shadow: 0 4px 12px rgba(5, 120, 78, 0.3);
        }
    </style>

    <!-- Header -->
    <div class="votes-header">
        <h1 class="votes-header-title">📋 Your Vote History</h1>
        <p class="votes-header-subtitle">Review all your submitted votes across all elections</p>
    </div>

    @if (count($votesByElection) > 0)
        @foreach ($votesByElection as $electionId => $data)
            <div class="election-votes-group">
                <h3 class="election-title-header">{{ $data['election']->title }}</h3>
                <p class="election-date-info">
                    📅 Election Created: {{ $data['election']->created_at->format('M d, Y \a\t g:i A') }}
                </p>

                <!-- Card View -->
                <div class="votes-grid">
                    @foreach ($data['votes'] as $positionName => $positionVotes)
                        @foreach ($positionVotes as $vote)
                            <div class="vote-card">
                                <div class="position-name">{{ $positionName }}</div>
                                <div class="candidate-voted">
                                    <div class="candidate-avatar">
                                        <svg viewBox="0 0 24 24">
                                            <path
                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </div>
                                    <div class="candidate-name">{{ $vote->candidate->name }}</div>
                                </div>
                                <span class="vote-badge">✓ Voted</span>
                                <div class="vote-timestamp">
                                    🕐 {{ $vote->created_at->format('M d \a\t g:i A') }}
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <!-- Summary Stats -->
                <div
                    style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0; font-size: 13px; color: #666;">
                    <strong>{{ $data['election']->positions->count() }}</strong> Position(s) •
                    <strong>{{ count($data['votes']) }}</strong> Vote(s) Cast
                </div>
            </div>
        @endforeach
    @else
        <div style="background: white; border-radius: 12px; padding: 40px;">
            <div class="empty-state-container">
                <div class="empty-state-icon">🗳️</div>
                <div class="empty-state-title">No Votes Yet</div>
                <p class="empty-state-text">You haven't cast any votes yet. Participate in active elections to see your
                    voting history here.</p>
                <a href="{{ route('voter.dashboard') }}" class="back-btn">← Back to Dashboard</a>
            </div>
        </div>
    @endif
@endsection
