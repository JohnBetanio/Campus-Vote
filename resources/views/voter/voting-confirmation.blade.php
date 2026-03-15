@extends('layouts.dashboard')

@section('title', 'Vote Confirmation')

@section('page-title', 'Vote Confirmation')

@section('content')

    <div class="confirmation-container">
        <div class="confirmation-card">
            <div class="success-icon">✓</div>

            <h1 class="confirmation-title">Your Vote Has Been Recorded</h1>

            <p class="confirmation-message">
                Thank you for participating in the election. Your votes have been securely recorded and cannot be changed.
            </p>

            <div class="election-info">
                <div class="election-info-label">Election</div>
                <div class="election-info-value">{{ $election->title }}</div>
            </div>

            <div class="info-box">
                <div class="info-box-title">⏱️ Results Timeline</div>
                <div class="info-box-text">
                    Results will be available after the election period ends. This ensures the integrity and fairness of the
                    election by preventing voters from being influenced by partial results.
                </div>
            </div>

            <div class="security-note">
                <span class="security-note-icon">🔐</span>
                Your vote is anonymous and securely stored. Only election administrators can view aggregated results after
                the election ends.
            </div>

            <div class="button-group" style="margin-top: 30px;">
                <a href="{{ route('voter.votes') }}" class="btn btn-primary">
                    📋 View Vote History
                </a>
                <a href="{{ route('voter.dashboard') }}" class="btn btn-secondary">
                    🏠 Return to Dashboard
                </a>
            </div>

            <p class="countdown-message">
                ⏳ You can return to check results once the election ends.
            </p>
        </div>
    </div>

        <style>
        .confirmation-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .confirmation-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(5, 150, 105, 0.15);
            text-align: center;
        }

        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce-in 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes bounce-in {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .confirmation-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a7a8f;
            margin-bottom: 15px;
        }

        .confirmation-message {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .election-info {
            background: #f0f9ff;
            border-left: 4px solid #059669;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: left;
        }

        .election-info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .election-info-value {
            font-size: 18px;
            font-weight: 700;
            color: #1a7a8f;
        }

        .info-box {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 30px;
            font-size: 14px;
            color: #92400e;
        }

        .info-box-title {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .info-box-text {
            line-height: 1.5;
        }

        .button-group {
            display: flex;
            gap: 12px;
            flex-direction: column;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(5, 150, 105, 0.3);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            border-color: #a7a7a7;
        }

        .checkmark {
            display: inline-block;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #10b981;
            margin-bottom: 20px;
            position: relative;
            animation: pop 0.5s ease-out;
        }

        @keyframes pop {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .checkmark::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 36px;
            color: white;
            font-weight: bold;
        }

        .security-note {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px;
            margin-top: 20px;
            font-size: 13px;
            color: #166534;
        }

        .security-note-icon {
            margin-right: 8px;
        }

        .countdown-message {
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }
    </style>
@endsection
