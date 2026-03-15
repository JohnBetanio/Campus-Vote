@extends('layouts.dashboard')

@section('title', 'Edit Profile')

@section('page-title', 'Edit Your Profile')
@section('page-subtitle', 'Update your voter information.')

@section('content')
    <style>
        .edit-form-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 40px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.25);
            position: relative;
            overflow: hidden;
        }

        .edit-form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .edit-form-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
            position: relative;
            z-index: 1;
        }

        .edit-form-header p {
            font-size: 15px;
            color: white;
            opacity: 0.95;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .edit-form-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
        }

        .form-section-title {
            font-size: 16px;
            font-weight: 700;
            color: #047857;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecfdf3;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            margin-bottom: 12px;
            color: #1f2937;
            font-size: 14px;
            letter-spacing: 0.3px;
        }

        .form-group input {
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #047857;
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
        }

        .form-group input::placeholder {
            color: #9ca3af;
        }

        .form-error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 8px;
            font-weight: 500;
        }

        .success-alert {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border: 1px solid rgba(5, 150, 105, 0.3);
            color: #065f46;
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .success-alert::before {
            content: '✓';
            font-size: 20px;
            flex-shrink: 0;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px solid #e5e7eb;
        }

        .user-info-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf3 100%);
            padding: 16px;
            border-radius: 10px;
            border: 1px solid rgba(5, 150, 105, 0.2);
            margin-bottom: 20px;
            font-size: 14px;
            color: #047857;
            font-weight: 600;
        }
    </style>

    <div class="edit-form-header">
        <h1>Edit Your Profile</h1>
        <p>Update your account information and password</p>
    </div>

    <div class="edit-form-container">
        @if (session('success'))
            <div class="success-alert">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('voter.profile.update') }}">
            @csrf
            @method('PUT')

            <!-- Personal Information Section -->
            <div class="form-section">
                <div class="form-section-title">👤 Personal Information</div>

                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $voter->name) }}" required
                        placeholder="Enter your full name">
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $voter->email) }}" required
                        placeholder="Enter your email address">
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="course">Course/Program</label>
                    <input id="course" name="course" type="text" value="{{ old('course', $voter->course) }}"
                        placeholder="e.g., Computer Science">
                    @error('course')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Security Section -->
            <div class="form-section">
                <div class="form-section-title">🔐 Security</div>

                <div class="user-info-box">
                    💡 Leave the password fields blank to keep your current password
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input id="password" name="password" type="password" placeholder="Enter a new password (optional)">
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        placeholder="Confirm your new password">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('voter.profile') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    💾 Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
