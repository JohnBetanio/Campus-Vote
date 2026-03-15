@extends('layouts.dashboard')

@section('title', 'Election Results')

@section('page-title', 'Election Results')
@section('page-subtitle', 'Monitor and analyze election outcomes.')

@section('content')
    <div class="card">

        <div style="display: flex; gap: 15px; margin-bottom: 20px; align-items: center;">
            <select class="form-control" style="max-width: 300px;"
                onchange="if(this.value) window.location.href='/admin/results/' + this.value">
                <option>Select Election</option>
                @foreach ($elections as $e)
                    <option value="{{ $e->id }}" {{ isset($election) && $election->id == $e->id ? 'selected' : '' }}>
                        {{ $e->title }}
                    </option>
                @endforeach
            </select>
            @if (isset($election))
                <button class="btn btn-success">Export Results</button>
                <button class="btn btn-primary" onclick="window.location.reload()">Refresh</button>
            @endif
        </div>
    </div>

    @if (isset($election))
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-number">{{ $stats['total_voters'] }}</div>
                <div class="stat-label">Total Voters</div>
            </div>

            <div class="stat-card blue">
                <div class="stat-number">{{ $stats['votes_cast'] }}</div>
                <div class="stat-label">Votes Cast</div>
            </div>

            <div class="stat-card red">
                <div class="stat-number">{{ $stats['participation_rate'] }}%</div>
                <div class="stat-label">Participation Rate</div>
            </div>

            <div class="stat-card orange">
                <div class="stat-number">{{ $stats['positions'] }}</div>
                <div class="stat-label">Positions</div>
            </div>
        </div>

        <div class="card">
            <div style="background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                <h3 style="font-size: 20px; margin-bottom: 5px;">{{ $election->title }}</h3>
                <span class="badge" style="background: white; color: var(--primary); margin-top: 10px;">ACTIVE</span>
            </div>

            @foreach ($results as $positionName => $candidates)
                <div style="margin-bottom: 30px;">
                    <div style="border-left: 4px solid #f9826c; padding-left: 15px; margin-bottom: 15px;">
                        <h4 style="font-size: 18px; font-weight: 600;">{{ $positionName }}</h4>
                    </div>

                    @foreach ($candidates as $candidate)
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                            <div class="candidate-avatar">
                                <svg viewBox="0 0 24 24">
                                    <path fill="white"
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; margin-bottom: 5px;">{{ $candidate['name'] }}</div>
                                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">{{ $candidate['votes'] }}
                                    votes ({{ $candidate['percentage'] }}%)</div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar"
                                        style="width: {{ $candidate['percentage'] }}%; background-color: #0366d6;"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <p class="empty-state-title">No Elections Available</p>
                <p class="empty-state-text">There are currently no elections created. Once an election is completed, the
                    results will appear here.</p>
                <a href="{{ route('admin.elections.create') }}" class="btn btn-success">Create Election</a>
            </div>
        </div>
    @endif
@endsection
