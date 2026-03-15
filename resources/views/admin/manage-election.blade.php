@extends('layouts.dashboard')

@section('title', 'Manage Elections')

@section('page-title', 'Manage Elections')
@section('page-subtitle', 'Create, edit, and monitor all elections in the system.')

@section('page-actions')
    <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">+ Create New Election</a>
@endsection

@section('content')
    <style>
        .election-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            border-left: 5px solid var(--primary);
            position: relative;
            overflow: hidden;
        }

        .election-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .election-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.10);
            transform: translateY(-4px);
            border-left-color: var(--primary-light);
        }

        .election-card:hover::before {
            opacity: 1;
        }

        .election-card.ended {
            border-left-color: #999;
            opacity: 0.85;
            background: #f9fafb;
        }

        .election-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            gap: 20px;
        }

        .election-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .election-card.ended .election-title {
            color: #666;
        }

        .election-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf3 100%);
            border-radius: 10px;
            border: 1px solid rgba(5, 150, 105, 0.2);
        }

        .meta-item {
            text-align: center;
        }

        .meta-value {
            font-size: 24px;
            font-weight: 700;
            color: #059669;
            display: block;
            margin-bottom: 4px;
        }

        .election-card.ended .meta-value {
            color: #999;
        }

        .meta-label {
            font-size: 11px;
            color: #666;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.active {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #065f46;
            border: 1px solid rgba(5, 150, 105, 0.3);
        }

        .status-badge.ended {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #666;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .position-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 15px;
        }

        .position-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf3 100%);
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #059669;
            border: 1px solid rgba(5, 150, 105, 0.2);
            transition: all 0.2s ease;
        }

        .position-card:hover {
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15);
            transform: translateY(-2px);
        }

        .position-card.ended {
            border-left-color: #999;
            opacity: 0.8;
        }

        .position-name {
            font-weight: 700;
            color: #047857;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .candidate-list {
            font-size: 12px;
            color: #666;
            line-height: 1.8;
        }

        .candidate-item {
            padding: 4px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .candidate-item::before {
            content: '•';
            color: #059669;
            font-weight: bold;
        }

        .action-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .action-btn {
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn-edit {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #0c4a6e;
            border: 1px solid rgba(3, 102, 214, 0.2);
        }

        .action-btn-edit:hover {
            background: linear-gradient(135deg, #bfdbfe 0%, #93c5fd 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(3, 102, 214, 0.15);
        }

        .action-btn-delete {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid rgba(220, 38, 38, 0.2);
        }

        .action-btn-delete:hover {
            background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
        }

        .create-election-btn {
            display: inline-block;
            padding: 12px 28px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .create-election-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
        }
    </style>

    <div id="electionsContainer">
        @forelse ($elections as $election)
            <div class="election-card {{ $election->status === 'ended' ? 'ended' : '' }}">
                <div class="election-header">
                    <div>
                        <h2 class="election-title">{{ $election->title }}</h2>
                        <small style="color: #6b7280;">Created {{ $election->created_at->format('M d, Y g:i A') }}</small>
                    </div>
                    <div>
                        <span class="status-badge {{ $election->status === 'active' ? 'active' : 'ended' }}">
                            <span>●</span>
                            {{ ucfirst($election->status) }}
                        </span>
                    </div>
                </div>

                @php
                    $electionVotedCount = \App\Models\Vote::where('election_id', $election->id)
                        ->distinct('voter_id')
                        ->count('voter_id');
                    $electionParticipationRate =
                        \App\Models\User::count() > 0
                            ? round(($electionVotedCount / \App\Models\User::count()) * 100, 1)
                            : 0;
                @endphp

                <div class="election-meta">
                    <div class="meta-item">
                        <span class="meta-value">{{ $election->positions->count() }}</span>
                        <span class="meta-label">Positions</span>
                    </div>
                    <div class="meta-item">
                        <span
                            class="meta-value">{{ $election->positions->sum(function ($p) {return $p->candidates->count();}) }}</span>
                        <span class="meta-label">Candidates</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-value">{{ $electionVotedCount }}</span>
                        <span class="meta-label">Votes Cast</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-value">{{ $electionParticipationRate }}%</span>
                        <span class="meta-label">Participation</span>
                    </div>
                </div>

                @if ($election->positions->count() > 0)
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                        <div
                            style="font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Positions & Candidates</div>
                        <div class="position-grid">
                            @foreach ($election->positions as $position)
                                <div class="position-card {{ $election->status === 'ended' ? 'ended' : '' }}">
                                    <div class="position-name">🎯 {{ $position->name }}</div>
                                    <div class="candidate-list">
                                        @forelse($position->candidates as $candidate)
                                            <div class="candidate-item">{{ $candidate->name }}</div>
                                        @empty
                                            <div style="color: #999; font-style: italic;">No candidates</div>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="action-group">
                    <a href="{{ route('admin.elections.edit', $election) }}" class="action-btn action-btn-edit">
                        ✏️ Edit
                    </a>
                    @if ($election->status === 'active')
                        <form method="POST" action="{{ route('admin.elections.end', $election) }}"
                            style="display: inline;">
                            @csrf
                            <button type="submit" class="action-btn action-btn-delete"
                                onclick="return confirm('End this election?')">
                                🛑 End Election
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.elections.destroy', $election) }}"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn action-btn-delete"
                            onclick="return confirm('Delete this election permanently?')">
                            🗑️ Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="card">
                <div style="text-align: center; padding: 60px 40px;">
                    <div style="font-size: 64px; margin-bottom: 20px; opacity: 0.5;">📋</div>
                    <h3 style="font-size: 24px; font-weight: 700; color: #1f2937; margin-bottom: 10px;">No Elections Created
                    </h3>
                    <p style="font-size: 16px; color: #6b7280; margin-bottom: 30px;">Start by creating your first election
                        to begin the voting process.</p>
                    <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">
                        ➕ Create Election
                    </a>
                </div>
            </div>
        @endforelse
    </div>
@endsection
