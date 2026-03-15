<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CampusVote')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --cv-primary: #32733d;
            --cv-primary-dark: #265930;
            --cv-light: #f8f8f8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--cv-light);
        }

        .auth-container {
            display: flex;
            min-height: 100vh;
        }

        .logo-text {
            font-size: 42px;
            color: var(--cv-primary);
            font-weight: 700;
        }

        .auth-right {
            flex: 1;
            background: linear-gradient(135deg, var(--cv-primary) 0%, #3d8f4a 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .login-card {
            background: white;
            padding: 50px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .login-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--cv-primary);
        }

        .form-input::placeholder {
            color: #999;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--cv-primary), #3d8f4a);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: #1a6b2e;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
        }

        .login-link {
            color: var(--cv-primary);
            text-decoration: none;
            font-size: 14px;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .error-message {
            background-color: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        /* Dashboard Layout */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background-color: #1e5128;
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            font-size: 24px;
            font-weight: 600;
            color: white;
            text-decoration: none;
        }

        .sidebar-welcome {
            font-size: 18px;
            padding: 20px;
            color: rgba(255, 255, 255, 0.9);
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
        }

        .nav-item {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: background-color 0.2s;
            font-size: 16px;
        }

        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            background-color: #22863a;
            border-left: 4px solid white;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-logout {
            width: 100%;
            padding: 12px;
            background-color: white;
            color: #1e5128;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-logout:hover {
            background-color: #f0f0f0;
        }

        .copyright {
            text-align: center;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 15px;
        }

        .main-content {
            margin-left: 260px;
            flex: 1;
            background-color: #f5f5f5;
            min-height: 100vh;
        }

        .top-bar {
            background-color: #1e5128;
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .top-bar-title {
            font-size: 18px;
            font-weight: 500;
        }

        .top-bar-right {
            font-size: 14px;
        }

        .content-area {
            padding: 40px;
        }

        /* Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-left: 4px solid;
        }

        .stat-card.green {
            border-left-color: #22863a;
        }

        .stat-card.blue {
            border-left-color: #0366d6;
        }

        .stat-card.red {
            border-left-color: #d73a49;
        }

        .stat-card.orange {
            border-left-color: #f9826c;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        /* Feature Cards */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .feature-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .feature-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: #0366d6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0256c1;
        }

        .btn-success {
            background-color: #22863a;
            color: white;
        }

        .btn-success:hover {
            background-color: #1a6b2e;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-danger {
            background-color: #d73a49;
            color: white;
        }

        .btn-danger:hover {
            background-color: #cb2431;
        }

        .btn-purple {
            background-color: #8b5cf6;
            color: white;
        }

        .btn-purple:hover {
            background-color: #7c3aed;
        }

        /* Announcements */
        .announcements-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .announcements-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .announcements-icon {
            font-size: 24px;
        }

        .announcements-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .announcement-item {
            padding: 15px;
            border-left: 4px solid;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .announcement-item:nth-child(1) {
            border-left-color: #0366d6;
        }

        .announcement-item:nth-child(2) {
            border-left-color: #d73a49;
        }

        .announcement-item:nth-child(3) {
            border-left-color: #22863a;
        }

        .announcement-item:nth-child(4) {
            border-left-color: #f9826c;
        }

        .announcement-text {
            font-size: 14px;
            color: #333;
            line-height: 1.5;
        }

        /* Welcome Section */
        .welcome-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .welcome-title {
            font-size: 36px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .welcome-subtitle {
            font-size: 16px;
            color: #666;
            font-style: italic;
        }

        .search-box {
            background: white;
            padding: 10px 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 14px;
            width: 250px;
        }

        /* Voting Page */
        .election-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .election-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .election-link {
            color: #0366d6;
            text-decoration: none;
        }

        .election-link:hover {
            text-decoration: underline;
        }

        .election-instruction {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }

        .positions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }

        .position-card {
            background: #e8e8e8;
            padding: 25px;
            border-radius: 8px;
        }

        .position-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .candidate-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            background: white;
            border-radius: 6px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .candidate-item:hover {
            background-color: #f8f9fa;
        }

        .candidate-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #0366d6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .candidate-avatar svg {
            width: 24px;
            height: 24px;
            fill: white;
        }

        .candidate-name {
            flex: 1;
            font-size: 16px;
            color: #333;
        }

        .candidate-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        /* Vote Summary Table */
        .vote-table {
            width: 100%;
            border-collapse: collapse;
        }

        .vote-table th,
        .vote-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e8e8e8;
        }

        .vote-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .vote-table td {
            font-size: 15px;
            color: #333;
        }

        .voted-status {
            color: #22863a;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .voted-candidate {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Results Page */
        .results-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .results-meta {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }

        .results-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .position-results {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .position-results-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .candidate-result {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .result-info {
            flex: 1;
        }

        .result-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .result-votes {
            font-size: 14px;
            color: #666;
        }

        .result-percentage {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            min-width: 60px;
            text-align: right;
        }

        .progress-bar-container {
            width: 100%;
            height: 8px;
            background-color: #e8e8e8;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-bar {
            height: 100%;
            background-color: #0366d6;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-bar.first {
            background-color: #0366d6;
        }

        .progress-bar.second {
            background-color: #6c757d;
        }

        .progress-bar.third {
            background-color: #6c757d;
        }

        /* Profile Page */
        .profile-card {
            background: #22863a;
            padding: 40px;
            border-radius: 8px;
            color: white;
            margin-bottom: 30px;
        }

        .profile-info {
            margin-bottom: 15px;
            font-size: 16px;
        }

        .profile-label {
            opacity: 0.8;
        }

        .profile-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn-dark {
            background-color: #1a5125;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-dark:hover {
            background-color: #14401d;
        }

        .profile-footer {
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .profile-footer a {
            color: #333;
            text-decoration: none;
        }

        .profile-footer a:hover {
            text-decoration: underline;
        }

        /* Admin specific styles */
        .admin-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .admin-table th {
            background-color: #e8e8e8;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
        }

        .admin-table td {
            padding: 15px;
            border-bottom: 1px solid #e8e8e8;
        }

        .admin-table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: #d4edda;
            color: #22863a;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #d73a49;
        }

        /* Election form */
        .election-form-section {
            background: #e8e8e8;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #22863a;
        }

        .position-section {
            background: white;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #22863a;
            margin-bottom: 15px;
        }

        .candidate-input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
        }

        .btn-add {
            background-color: #17a2b8;
            color: white;
        }

        .btn-add:hover {
            background-color: #138496;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 8px;
            border: 2px dashed #ddd;
        }

        .empty-state-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .empty-state-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        /* Google Sign-In Button */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px 20px;
            margin-bottom: 20px;
            background: white;
            border: 1px solid #dadce0;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #3c4043;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .btn-google:hover {
            background: #f8f9fa;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border-color: #d2d4d6;
        }

        .google-icon {
            margin-right: 12px;
            flex-shrink: 0;
        }

        /* Divider */
        .divider {
            position: relative;
            text-align: center;
            margin: 24px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #e0e0e0;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .divider span {
            background: white;
            padding: 0 12px;
            font-size: 13px;
            color: #666;
            position: relative;
            z-index: 1;
        }

        /* Success Message */
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* Campus Domain Note */
        .campus-domain-note {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 16px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        /* Adjust existing button spacing */
        .btn-login {
            margin-top: 8px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .btn-google {
                font-size: 13px;
                padding: 10px 16px;
            }

            .divider {
                margin: 20px 0;
            }

            .campus-domain-note {
                font-size: 11px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @yield('content')
    @stack('scripts')
</body>

</html>
