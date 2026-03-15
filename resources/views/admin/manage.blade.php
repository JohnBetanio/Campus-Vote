<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Vote - Manage Election</title>
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="logo">CampusVote</div>
            <div class="menu">
                <a href="{{ route('admin.dashboard') }}">Home</a>
                <a href="{{ route('admin.manage') }}" class="active">Manage Election</a>
                <a href="{{ route('admin.create') }}">Create Election</a>
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
            <span>Manage Election</span>
            <span>CampusVote</span>
        </div>

        <div class="content">
            <div class="no-election-box">
                <h2>No Active Election</h2>
                <p>There is currently no active election to manage.</p>
                <button class="create-btn" onclick="location.href='{{ route('admin.create') }}'">Create New Election</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin-script.js') }}"></script>
</body>
</html>
