<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Vote - Admin Login</title>
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
</head>
<body>
    <div class="container">
        <!-- LEFT SIDE -->
        <div class="left">
            <img src="{{ asset('images/download.webp') }}" alt="Campus Vote Logo">
            <h1>Campus Vote</h1>
            <p>
                Manage the Campus Vote platform, monitor voting activities, and
                oversee candidate information. Ensure a fair and secure election
                process for all students.
            </p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="right">
            <div class="login-box">
                <h2>Admin Login</h2>
                <form id="adminForm">
                    <input type="email" id="adminEmail" placeholder="Admin Email" required>
                    <input type="password" id="adminPassword" placeholder="Password" required>
                    <button type="submit">Login</button>
                </form>
                <a href="{{ route('user.login') }}" id="backUser">← Back to User Login</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin-script.js') }}"></script>
</body>
</html>
