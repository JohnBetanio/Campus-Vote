@extends('layouts.dashboard')

@section('title', 'Edit Election')

@section('page-title', 'Edit Election')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('admin.elections.update', $election) }}" id="electionForm">
            @csrf
            @method('PUT')

            <div style="background: #e8e8e8; padding: 20px; border-radius: 6px; margin-bottom: 20px;">
                <label class="form-label">Election Title:</label>
                <input type="text" name="title" class="form-control" value="{{ $election->title }}" required
                    style="margin-bottom: 15px;">

                <button type="button" class="btn btn-success" onclick="addPosition()">+ Add Position</button>
            </div>

            <div id="positionsContainer">
                @foreach ($election->positions as $index => $position)
                    <div class="position-section" id="position-{{ $index }}">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div style="flex: 1; margin-right: 10px;">
                                <label class="form-label">Position Name:</label>
                                <input type="text" name="positions[{{ $index }}][name]" class="form-control"
                                    value="{{ $position->name }}" required>
                                <label class="form-label" style="margin-top:8px;">Max selections for this position:</label>
                                <input type="number" name="positions[{{ $index }}][max_votes]" class="form-control"
                                    value="{{ $position->max_votes ?? 1 }}" min="1" style="width:120px;">
                            </div>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="removePosition({{ $index }})" style="margin-top: 25px;">Delete</button>
                        </div>

                        <div id="candidates-{{ $index }}">
                            @foreach ($position->candidates as $candidate)
                                <div class="candidate-input-group">
                                    <input type="text" name="positions[{{ $index }}][candidates][]"
                                        class="form-control" value="{{ $candidate->name }}" required>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="this.parentElement.remove()">Delete</button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-add btn-sm" onclick="addCandidate({{ $index }})">+ Add
                            Candidate</button>
                    </div>
                @endforeach
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.elections.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Update Election</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let positionCount = {{ $election->positions->count() }};

            function addPosition() {
                positionCount++;
                const container = document.getElementById('positionsContainer');
                const positionDiv = document.createElement('div');
                positionDiv.className = 'position-section';
                positionDiv.id = `position-${positionCount}`;
                positionDiv.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <div style="flex: 1; margin-right: 10px;">
                <label class="form-label">Position Name:</label>
                <input type="text" name="positions[${positionCount}][name]" class="form-control" placeholder="Enter position name" required>
                <label class="form-label" style="margin-top:8px;">Max selections for this position:</label>
                <input type="number" name="positions[${positionCount}][max_votes]" class="form-control" value="1" min="1" style="width:120px;">
            </div>
            <button type="button" class="btn btn-danger btn-sm" onclick="removePosition(${positionCount})" style="margin-top: 25px;">Delete</button>
        </div>
        
        <div id="candidates-${positionCount}">
            <div class="candidate-input-group">
                <input type="text" name="positions[${positionCount}][candidates][]" class="form-control" placeholder="Candidate 1 Name" required>
            </div>
        </div>
        
        <button type="button" class="btn btn-add btn-sm" onclick="addCandidate(${positionCount})">+ Add Candidate</button>
    `;
                container.appendChild(positionDiv);
            }

            function removePosition(id) {
                if (confirm('Are you sure you want to remove this position?')) {
                    document.getElementById(`position-${id}`).remove();
                }
            }

            function addCandidate(positionId) {
                const container = document.getElementById(`candidates-${positionId}`);
                const candidateCount = container.querySelectorAll('.candidate-input-group').length + 1;
                const candidateDiv = document.createElement('div');
                candidateDiv.className = 'candidate-input-group';
                candidateDiv.innerHTML = `
        <input type="text" name="positions[${positionId}][candidates][]" class="form-control" placeholder="Candidate ${candidateCount} Name" required>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">Delete</button>
    `;
                container.appendChild(candidateDiv);
            }
        </script>
    @endpush
@endsection
