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
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }

        .position-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 250, 252, 0.95) 100%);
            backdrop-filter: blur(20px);
            padding: 20px;
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.5);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .position-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #059669 0%, #10b981 50%, #06b6d4 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .position-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 12px 40px rgba(5, 150, 105, 0.15);
            border-color: rgba(5, 150, 105, 0.3);
        }

        .position-card:hover::before {
            transform: scaleX(1);
        }

        .position-card.ended {
            opacity: 0.7;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-color: #cbd5e1;
        }

        .position-card.ended::before {
            background: linear-gradient(90deg, #64748b 0%, #94a3b8 100%);
        }

        .position-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .position-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
            flex-shrink: 0;
        }

        .position-card.ended .position-icon {
            background: linear-gradient(135deg, #64748b 0%, #94a3b8 100%);
            box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
        }

        .position-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 16px;
            letter-spacing: -0.3px;
            line-height: 1.3;
        }

        .position-card.ended .position-name {
            color: #64748b;
        }

        .candidate-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .candidate-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 10px;
            border: 1px solid rgba(226, 232, 240, 0.5);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .candidate-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .candidate-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: rgba(5, 150, 105, 0.3);
        }

        .candidate-item:hover::before {
            transform: scaleX(1);
        }

        .candidate-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .candidate-info {
            flex: 1;
        }

        .candidate-name {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            line-height: 1.4;
        }

        .candidate-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #059669;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid rgba(5, 150, 105, 0.2);
        }

        .position-card.ended .candidate-badge {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #64748b;
            border-color: rgba(100, 116, 139, 0.2);
        }

        .no-candidates {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
            font-style: italic;
            font-size: 13px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 10px;
            border: 1px dashed #cbd5e1;
        }

        .positions-section {
            margin-top: 24px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.3px;
        }

        .section-icon {
            font-size: 20px;
        }

        .action-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(226, 232, 240, 0.5);
        }

        .action-btn {
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
            min-width: 120px;
            justify-content: center;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn-edit {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .action-btn-edit:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .action-btn-end {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .action-btn-end:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        }

        .action-btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .action-btn-delete:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        .action-icon {
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
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
                        <div class="positions-section">
                            <h3 class="section-title">
                                <span class="section-icon">📋</span>
                                Positions & Candidates
                            </h3>
                            <div class="position-grid">
                                @foreach ($election->positions as $position)
                                    <div class="position-card {{ $election->status === 'ended' ? 'ended' : '' }}">
                                        <div class="position-header">
                                            <div class="position-icon">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path
                                                        d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" />
                                                    <path d="M12 22V8" />
                                                    <path d="M12 8L2 12" />
                                                    <path d="M12 8l10 4" />
                                                </svg>
                                            </div>
                                            <div class="position-name">{{ $position->name }}</div>
                                        </div>
                                        <div class="candidate-list">
                                            @forelse($position->candidates as $candidate)
                                                <div class="candidate-item">
                                                    <div class="candidate-avatar">
                                                        {{ mb_substr($candidate->name, 0, 1, 'UTF-8') }}
                                                    </div>
                                                    <div class="candidate-info">
                                                        <div class="candidate-name">{{ $candidate->name }}</div>
                                                        <div class="candidate-badge">Candidate</div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="no-candidates">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" style="margin-bottom: 4px;">
                                                        <circle cx="12" cy="12" r="10" />
                                                        <line x1="12" y1="8" x2="12" y2="12" />
                                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                                    </svg>
                                                    No candidates added
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="action-group">
                    <a href="{{ route('admin.elections.edit', $election) }}" class="action-btn action-btn-edit">
                        <span class="action-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </span>
                        Edit Election
                    </a>
                    @if ($election->status === 'active')
                        <form method="POST" action="{{ route('admin.elections.end', $election) }}"
                            style="display: inline;">
                            @csrf
                            <button type="submit" class="action-btn action-btn-end"
                                onclick="return confirm('Are you sure you want to end this election? This action cannot be undone.')">
                                <span class="action-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="10" rx="2" ry="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    </svg>
                                </span>
                                End Election
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.elections.destroy', $election) }}"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn action-btn-delete"
                            onclick="return confirm('Are you sure you want to permanently delete this election? This action cannot be undone and all voting data will be lost.')">
                            <span class="action-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6" />
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    <line x1="10" y1="11" x2="10" y2="17" />
                                    <line x1="14" y1="11" x2="14" y2="17" />
                                </svg>
                            </span>
                            Delete Election
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="card">
                <div style="text-align: center; padding: 60px 40px;">
                    <div style="font-size: 64px; margin-bottom: 20px; opacity: 0.5;">📋</div>
                    <h3 style="font-size: 24px; font-weight: 700; color: #1f2937; margin-bottom: 10px;">No Elections
                        Created
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
