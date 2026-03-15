@extends('layouts.dashboard')

@section('title', 'Create Election')

@section('page-title', 'Create Election')

@section('content')
    <style>
        .election-form {
            max-width: 900px;
            margin: 0 auto;
        }

        .form-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 40px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.25);
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .form-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 10px 0;
            position: relative;
            z-index: 1;
        }

        .form-header p {
            font-size: 16px;
            opacity: 0.95;
            color: white;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .section-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #047857 0%, #059669 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .section-card:hover::before {
            transform: scaleX(1);
        }

        .section-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
        }

        .section-header {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-header-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .form-label {
            font-weight: 700;
            display: block;
            margin-bottom: 12px;
            color: #1f2937;
            font-size: 15px;
            letter-spacing: 0.3px;
        }

        .position-controls {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 15px;
            align-items: flex-end;
        }

        .position-controls>div {
            flex: 1;
            min-width: 200px;
        }

        .candidate-input-group {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            align-items: center;
        }

        .candidate-input-group input {
            flex: 1;
        }

        .btn-add,
        .add-candidate-btn {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border-radius: 10px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add:hover,
        .add-candidate-btn:hover {
            background: linear-gradient(135deg, #138496 0%, #0d6780 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
        }

        .btn-add.small,
        .add-candidate-btn.small {
            padding: 8px 16px;
            font-size: 13px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e5e7eb;
        }

        .position-number {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
        }

        .candidates-section {
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.03) 0%, rgba(5, 150, 105, 0.01) 100%);
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
            border: 1px solid rgba(5, 150, 105, 0.1);
        }

        .candidates-label {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #047857;
        }
    </style>

    <div class="form-header">
        <h1>Create New Election</h1>
        <p>Set up a new election with positions and candidates</p>
    </div>

    <form method="POST" action="{{ route('admin.elections.store') }}" id="electionForm">
        @csrf
        <div class="election-form">
            <div class="section-card">
                <h2 class="section-header">
                    <span class="section-header-icon">📋</span>
                    Election Details
                </h2>
                <div class="form-group">
                    <label class="form-label" for="title">Election Title *</label>
                    <input type="text" id="title" name="title" class="form-control"
                        placeholder="Enter election name" required value="{{ old('title') }}">
                </div>
            </div>

            <div id="positionsContainer"></div>

            <div style="text-align: center; margin-bottom: 20px;">
                <button type="button" class="btn-add" onclick="addPosition()">
                    ➕ Add Position
                </button>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.elections.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Election</button>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            let positionCount = 0;

            function addPosition() {
                positionCount++;
                const container = document.getElementById('positionsContainer');
                const positionDiv = document.createElement('div');
                positionDiv.className = 'section-card';
                positionDiv.id = `position-${positionCount}`;
                positionDiv.innerHTML = `
                    <h2 class="section-header">
                        <span class="section-header-icon">${positionCount}</span>
                        Position ${positionCount}
                    </h2>
                    <div class="position-controls">
                        <div>
                            <label class="form-label">Position Name *</label>
                            <input type="text" name="positions[${positionCount}][name]" class="form-control" placeholder="e.g., President, Secretary" required>
                        </div>
                        <div style="min-width: 150px;">
                            <label class="form-label">Max Votes Allowed</label>
                            <input type="number" name="positions[${positionCount}][max_votes]" class="form-control" value="1" min="1">
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" style="align-self: flex-end;" onclick="removePosition(${positionCount})">
                            🗑️ Remove
                        </button>
                    </div>
                    <div class="candidates-section">
                        <div class="candidates-label">Candidates</div>
                        <div id="candidates-${positionCount}">
                            <div class="candidate-input-group">
                                <input type="text" name="positions[${positionCount}][candidates][]" class="form-control" placeholder="Candidate 1 Name" required>
                            </div>
                        </div>
                        <button type="button" class="btn-add small" onclick="addCandidate(${positionCount})" style="margin-top: 12px;">
                            ➕ Add Candidate
                        </button>
                    </div>
                `;
                container.appendChild(positionDiv);
            }

            function removePosition(id) {
                const element = document.getElementById(`position-${id}`);
                element.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => element.remove(), 300);
            }

            function addCandidate(positionId) {
                const container = document.getElementById(`candidates-${positionId}`);
                const candidateCount = container.querySelectorAll('.candidate-input-group').length + 1;
                const candidateDiv = document.createElement('div');
                candidateDiv.className = 'candidate-input-group';
                candidateDiv.innerHTML = `
                    <input type="text" name="positions[${positionId}][candidates][]" class="form-control" placeholder="Candidate ${candidateCount} Name" required>
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">
                        ✕ Delete
                    </button>
                `;
                container.appendChild(candidateDiv);
            }

            // Add at least one position on load
            document.addEventListener('DOMContentLoaded', function() {
                addPosition();
            });

            // Add smooth animation
            const style = document.createElement('style');
            style.innerHTML = `
                @keyframes slideOut {
                    to {
                        opacity: 0;
                        transform: translateX(-20px);
                    }
                }
            `;
            document.head.appendChild(style);
        </script>
    @endpush
@endsection
