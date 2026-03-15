<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Vote - Create Election</title>
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="logo">CampusVote</div>
            <div class="menu">
                <a href="{{ route('admin.dashboard') }}">Home</a>
                <a href="{{ route('admin.manage') }}">Manage Election</a>
                <a href="{{ route('admin.create') }}" class="active">Create Election</a>
                <a href="{{ route('admin.voters') }}">Voters</a>
                <a href="{{ route('admin.results') }}">Results</a>
                <a href="{{ route('admin.announcements') }}">Announcements</a>
                <a href="{{ route('admin.login') }}" id="logout">Logout</a>
            </div>
        </div>
        <div class="footer">© 2025 CampusVote. All rights reserved.</div>
    </div>

    <div class="main">
        <div class="header">
            <span>Create Election</span>
            <span>CampusVote</span>
        </div>

        <div class="content">
            <div class="form-box" id="electionForm">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 20px;">
                    <label style="font-weight: bold; font-size: 16px;">Election Title:</label>
                    <input type="text" id="electionTitle" placeholder="Enter election name" style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
                    <button class="add-btn" id="addPositionBtn">+ Add Position</button>
                </div>

                <div id="positionsContainer"></div>

                <div class="action-buttons">
                    <button class="cancel-btn" id="cancelBtn">Cancel</button>
                    <button class="save-btn" id="saveElection">Create Election</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin-script.js') }}"></script>
</body>
</html>
