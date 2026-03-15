@extends('layouts.dashboard')

@section('title', 'Profile')

@section('page-title', 'My Profile')
@section('page-subtitle', 'Manage your voter information.')

@section('content')
    <style>
        .profile-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 50px 40px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(5, 150, 105, 0.25);
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .profile-header-content {
            position: relative;
            z-index: 1;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 56px;
            margin-bottom: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .profile-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 8px 0;
        }

        .profile-header p {
            font-size: 16px;
            opacity: 0.95;
            color: white;
            margin: 0;
        }

        .profile-info-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .profile-info-section:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.10);
        }

        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .profile-field {
            padding: 20px;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf3 100%);
            border-radius: 12px;
            border: 1px solid rgba(5, 150, 105, 0.2);
            transition: all 0.3s ease;
        }

        .profile-field:hover {
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.1);
            transform: translateY(-2px);
        }

        .profile-field-label {
            font-size: 12px;
            font-weight: 700;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        .profile-field-value {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
        }

        .profile-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e5e7eb;
        }

        .profile-footer-section {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        .profile-footer-links {
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .profile-footer-links a {
            color: #059669;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .profile-footer-links a:hover {
            color: #047857;
            text-decoration: underline;
        }

        .google-account {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
            border: 1px solid rgba(3, 102, 214, 0.2);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .google-account img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(3, 102, 214, 0.2);
        }

        .google-account p {
            margin: 0;
            color: #0c4a6e;
            font-weight: 600;
            font-size: 14px;
        }
    </style>

    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar">👤</div>
            <h1>{{ $voter->name }}</h1>
            <p>Manage your account and preferences</p>
        </div>
    </div>

    <div class="profile-info-section">
        <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 25px;">Account Information</h2>

        <div class="profile-info-grid">
            <div class="profile-field">
                <span class="profile-field-label">📧 Email</span>
                <span class="profile-field-value">{{ $voter->email }}</span>
            </div>
            <div class="profile-field">
                <span class="profile-field-label">👤 Full Name</span>
                <span class="profile-field-value">{{ $voter->name }}</span>
            </div>
            <div class="profile-field">
                <span class="profile-field-label">📚 Course</span>
                <span class="profile-field-value">{{ $voter->course ?? 'Not specified' }}</span>
            </div>
        </div>

        @if ($voter->google_id)
            <div class="google-account">
                <img src="{{ $voter->avatar }}" alt="Avatar">
                <p>✓ Signed in with Google: {{ $voter->campus_email }}</p>
            </div>
        @endif

        <div class="profile-actions">
            <a href="{{ route('voter.profile.edit') }}" class="btn btn-primary">
                ✏️ Edit Profile
            </a>
            <form method="POST" action="{{ route('voter.logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-danger">
                    🚪 Log Out
                </button>
            </form>
        </div>
    </div>

    <div class="profile-footer-section">
        <h3
            style="font-size: 14px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 15px;">
            Quick Links</h3>
        <div class="profile-footer-links">
            <a href="#">About CampusVote</a>
            <a href="#">Help Center</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
    </div>
@endsection
