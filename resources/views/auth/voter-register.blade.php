@extends('layouts.app')

@section('title', 'Student Registration - CampusVote')

@section('content')
    <div class="auth-container">

        <div class="auth-right">
            <div class="login-card">
                <h1 class="login-title">Student Registration</h1>

                @if ($errors->any())
                    <div class="error-message">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('voter.register') }}">
                    @csrf

                    <div class="form-group">
                        <input type="text" name="name" class="form-input" placeholder="Full Name"
                            value="{{ old('name') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <input type="email" name="email" class="form-input" placeholder="Student Email"
                            value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <input type="text" name="course" class="form-input" placeholder="Course (e.g., BSIT)"
                            value="{{ old('course') }}" required>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" class="form-input" placeholder="Password" required>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="form-input"
                            placeholder="Confirm Password" required>
                    </div>

                    <button type="submit" class="btn-login">Register</button>
                </form>

                <div class="login-footer">
                    <a href="{{ route('voter.login') }}" class="login-link">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>
@endsection
