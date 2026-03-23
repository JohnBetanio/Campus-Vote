<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CampusVote')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    @include('layouts.styles')
    @stack('styles')
</head>

<body>
    <div class="dashboard-container">

        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="{{ auth()->guard('voter')->check() ? route('voter.dashboard') : route('admin.dashboard') }}"
                    class="sidebar-logo">
                    <span class="sidebar-logo-icon">C</span>
                    <span class="sidebar-logo-text">CampusVote</span>
                </a>
            </div>

            @if (auth()->guard('voter')->check())
                {{-- VOTER SIDEBAR --}}

                <nav class="sidebar-nav">
                    <a href="{{ route('voter.dashboard') }}"
                        class="nav-item {{ request()->routeIs('voter.dashboard') ? 'active' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('voter.vote') }}"
                        class="nav-item {{ request()->routeIs('voter.vote') ? 'active' : '' }}">
                        Vote Now
                    </a>
                    <a href="{{ route('voter.votes') }}"
                        class="nav-item {{ request()->routeIs('voter.votes') ? 'active' : '' }}">
                        View Votes
                    </a>
                    <a href="{{ route('voter.results') }}"
                        class="nav-item {{ request()->routeIs('voter.results') ? 'active' : '' }}">
                        View Results
                    </a>
                    <a href="{{ route('voter.profile') }}"
                        class="nav-item {{ request()->routeIs('voter.profile') ? 'active' : '' }}">
                        Profile
                    </a>
                </nav>

                <div class="sidebar-footer">
                    <form method="POST" action="{{ route('voter.logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout">Log out</button>
                    </form>
                    <div class="copyright">© 2025 CampusVote. All rights reserved.</div>
                </div>
            @elseif(auth()->guard('admin')->check())
                {{-- ADMIN SIDEBAR --}}

                <nav class="sidebar-nav">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('admin.elections.index') }}"
                        class="nav-item {{ request()->routeIs('admin.elections.index') || request()->routeIs('admin.elections.edit') ? 'active' : '' }}">
                        Manage Election
                    </a>
                    <a href="{{ route('admin.elections.create') }}"
                        class="nav-item {{ request()->routeIs('admin.elections.create') ? 'active' : '' }}">
                        Create Election
                    </a>
                    <a href="{{ route('admin.voters.index') }}"
                        class="nav-item {{ request()->routeIs('admin.voters.*') ? 'active' : '' }}">
                        Voters
                    </a>
                    <a href="{{ route('admin.results.index') }}"
                        class="nav-item {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                        Results
                    </a>
                    <a href="{{ route('admin.announcements.index') }}"
                        class="nav-item {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                        Announcements
                    </a>
                </nav>

                <div class="sidebar-footer">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                    <div class="copyright">© 2025 CampusVote. All rights reserved.</div>
                </div>
            @endif
        </aside>

        <main class="main-content">
            {{-- Donezo-style top header: search + user profile --}}
            <header class="top-bar">
                <input type="search" placeholder="Search elections and results" class="search-box" aria-label="Search">
                <div class="top-bar-right">
                    @if (auth()->guard('voter')->check())
                        @php $user = auth()->guard('voter')->user(); @endphp
                        <div class="user-profile">
                            <div class="user-avatar">{{ mb_substr($user->name ?? 'U', 0, 1) }}</div>
                            <div class="user-info">
                                <span class="user-name">{{ $user->name ?? 'Voter' }}</span>
                                <span class="user-email">{{ $user->email ?? '' }}</span>
                            </div>
                        </div>
                    @else
                        @php $admin = auth()->guard('admin')->user(); @endphp
                        <div class="user-profile">
                            <div class="user-avatar user-avatar-admin">A</div>
                            <div class="user-info">
                                <span class="user-name">{{ $admin->name ?? 'Admin' }}</span>
                                <span class="user-email">{{ $admin->email ?? 'CampusVote Administration' }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </header>

            <div class="content-area">
                {{-- Page header: title + subtitle (Donezo style) --}}
                <div class="page-header">
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                    <p class="page-subtitle">@yield('page-subtitle', 'Plan, prioritize, and accomplish your tasks with ease.')</p>
                    @hasSection('page-actions')
                        <div class="page-actions">@yield('page-actions')</div>
                    @endif
                </div>

                @if (session('success'))
                    <div
                        class="mb-5 flex items-start gap-3 rounded-lg border-l-4 border-emerald-500 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        <span
                            class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/10">
                            ✓
                        </span>
                        <p class="leading-relaxed">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-5 flex items-start gap-3 rounded-lg border-l-4 border-rose-500 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                        <span
                            class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-rose-500/10">
                            !
                        </span>
                        <p class="leading-relaxed">{{ session('error') }}</p>
                    </div>
                @endif

                @if (session('info'))
                    <div
                        class="mb-5 flex items-start gap-3 rounded-lg border-l-4 border-sky-500 bg-sky-50 px-4 py-3 text-sm text-sky-800">
                        <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-sky-500/10">
                            i
                        </span>
                        <p class="leading-relaxed">{{ session('info') }}</p>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>

</html>
