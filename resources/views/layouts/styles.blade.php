<style>
    /* ===== DONEZO-STYLE DESIGN SYSTEM ===== */
    /* Color Palette - Deep green & light gray theme */
    :root {
        --primary: #32733d;
        --primary-dark: #265930;
        --primary-light: #3d8f4a;
        --primary-lighter: #ecfdf3;
        --secondary: #1a7a8f;
        --accent: #f59e0b;
        --danger: #dc2626;
        --success: #32733d;
        --warning: #f59e0b;
        --info: #3b82f6;
        --light: #f8f8f8;
        --lighter: #fafafa;
        --border: #e5e7eb;
        --text-dark: #1f2937;
        --text-light: #6b7280;
        --text-lighter: #9ca3af;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        --radius: 12px;
        --radius-lg: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', sans-serif;
        background-color: var(--light);
        color: var(--text-dark);
        overflow-x: hidden;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Typography */
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-weight: 600;
        line-height: 1.2;
        color: var(--text-dark);
    }

    h1 {
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
    }

    h2 {
        font-size: 2rem;
        margin-bottom: 1.25rem;
    }

    h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    h4 {
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
    }

    h5 {
        font-size: 1.125rem;
        margin-bottom: 0.5rem;
    }

    h6 {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    p {
        margin-bottom: 1rem;
        color: var(--text-light);
    }

    a {
        color: var(--primary);
        text-decoration: none;
        transition: var(--transition);
    }

    a:hover {
        color: var(--primary-dark);
    }

    /* Auth Container */
    .auth-container {
        display: flex;
        min-height: 100vh;
        background: var(--light);
    }

    .logo-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-lg);
        font-size: 40px;
    }

    .logo-text {
        font-size: 42px;
        color: var(--primary);
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .auth-right {
        flex: 1;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
        position: relative;
        overflow: hidden;
    }

    .auth-right::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(30px);
        }
    }

    .login-card {
        background: white;
        padding: 50px;
        border-radius: 16px;
        width: 100%;
        max-width: 400px;
        box-shadow: var(--shadow-xl);
        position: relative;
        z-index: 1;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .login-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 30px;
        text-align: center;
        color: var(--text-dark);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 15px;
        background: var(--lighter);
        transition: var(--transition);
        font-family: inherit;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }

    .form-input::placeholder {
        color: var(--text-lighter);
    }

    .btn-login {
        width: 100%;
        padding: 14px 24px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: var(--shadow-md);
        margin-top: 8px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .login-footer {
        text-align: center;
        margin-top: 20px;
    }

    .login-link {
        color: var(--primary);
        text-decoration: none;
        font-size: 14px;
        transition: var(--transition);
        display: inline-block;
        margin: 8px 0;
    }

    .login-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .error-message {
        background-color: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    /* Dashboard Layout - Donezo style */
    .dashboard-container {
        display: flex;
        min-height: 100vh;
        width: 100%;
    }

    .sidebar {
        width: 260px;
        background: white;
        color: var(--text-dark);
        display: flex;
        flex-direction: column;
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1000;
        box-shadow: var(--shadow-md);
        border-right: 1px solid var(--border);
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: var(--lighter);
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }

    .sidebar-header {
        padding: 24px 20px;
        border-bottom: 1px solid var(--border);
    }

    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: var(--transition);
    }

    .sidebar-logo-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
    }

    .sidebar-logo-text {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }

    .sidebar-nav {
        flex: 1;
        padding: 16px 12px;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        color: var(--text-light);
        text-decoration: none;
        transition: var(--transition);
        font-size: 15px;
        font-weight: 500;
        border-radius: var(--radius);
        margin-bottom: 4px;
        position: relative;
    }

    .nav-item:hover {
        background-color: var(--lighter);
        color: var(--text-dark);
    }

    .nav-item.active {
        background-color: var(--primary);
        color: white;
        font-weight: 600;
    }

    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid var(--border);
    }

    .btn-logout {
        width: 100%;
        padding: 12px;
        background-color: var(--lighter);
        color: var(--text-dark);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-logout:hover {
        background-color: #fee2e2;
        color: var(--danger);
        border-color: #fecaca;
    }

    .copyright {
        text-align: center;
        font-size: 12px;
        color: var(--text-lighter);
        margin-top: 15px;
    }

    /* Mobile app card (Donezo-style bottom CTA) - optional */
    .sidebar-app-card {
        margin: 16px 12px;
        padding: 20px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: var(--radius);
        color: white;
    }

    /* Main Content */
    .main-content {
        margin-left: 260px;
        width: calc(100% - 260px);
        min-height: 100vh;
        background-color: var(--light);
    }

    .top-bar {
        background: white;
        color: var(--text-dark);
        padding: 16px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        box-shadow: var(--shadow-sm);
        border-bottom: 1px solid var(--border);
    }

    .search-box {
        flex: 1;
        max-width: 400px;
        background: var(--lighter);
        padding: 12px 20px;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        font-size: 14px;
        transition: var(--transition);
        position: relative;
    }

    .search-box::placeholder {
        color: var(--text-lighter);
    }

    .search-box:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(50, 115, 61, 0.1);
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
    }

    .user-avatar-admin {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%);
    }

    .user-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .user-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
    }

    .user-email {
        font-size: 12px;
        color: var(--text-lighter);
    }

    .content-area {
        padding: 32px 40px;
        width: 100%;
        max-width: 100%;
    }

    .page-header {
        margin-bottom: 28px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 8px 0;
        letter-spacing: -0.5px;
    }

    .page-subtitle {
        font-size: 15px;
        color: var(--text-light);
        margin: 0 0 16px 0;
    }

    .page-actions {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }

    /* Cards */
    .card {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 30px;
        width: 100%;
        border: 1px solid var(--border);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.10);
        transform: translateY(-2px);
        border-color: var(--primary);
    }

    .card:hover::before {
        opacity: 1;
    }

    .card-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--text-dark);
    }

    .card-subtitle {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 30px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
        width: 100%;
    }

    /* Donezo-style stat cards: first green, others light gray */
    .stat-card {
        background: white;
        padding: 24px;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-card.primary,
    .stat-card.green {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        color: white;
    }

    .stat-card.primary .stat-number,
    .stat-card.green .stat-number {
        color: white;
    }

    .stat-card.primary .stat-label,
    .stat-card.green .stat-label {
        color: rgba(255, 255, 255, 0.9);
    }

    .stat-card.primary .stat-subtitle,
    .stat-card.green .stat-subtitle {
        color: rgba(255, 255, 255, 0.8);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card.blue { border-left: 4px solid #3b82f6; }
    .stat-card.red { border-left: 4px solid var(--danger); }
    .stat-card.orange { border-left: 4px solid var(--warning); }

    .stat-card .stat-icon {
        position: absolute;
        top: 16px;
        right: 16px;
        opacity: 0.15;
        font-size: 32px;
    }

    .stat-number {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 6px;
        color: var(--text-dark);
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-light);
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .stat-subtitle {
        font-size: 12px;
        color: var(--text-lighter);
        margin-top: 6px;
    }

    .stat-card.blue .stat-number { color: #1d4ed8; }
    .stat-card.red .stat-number { color: var(--danger); }
    .stat-card.orange .stat-number { color: var(--warning); }

    /* ===== Unified KPI Cards (Admin + Voter) ===== */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }
    @media (max-width: 1200px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .kpi-grid { grid-template-columns: 1fr; } }

    .kpi-card {
        display: block;
        background: white;
        border-radius: var(--radius-lg);
        padding: 24px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        position: relative;
        overflow: hidden;
    }
    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: rgba(50, 115, 61, 0.3);
    }
    .kpi-card:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }

    .kpi-card--primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        color: white;
    }
    .kpi-card--primary:hover { box-shadow: 0 12px 30px rgba(50, 115, 61, 0.35); }

    .kpi-card__icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 16px;
    }
    .kpi-card:not(.kpi-card--primary) .kpi-card__icon {
        background: rgba(50, 115, 61, 0.12);
        color: var(--primary);
    }
    .kpi-card--primary .kpi-card__icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .kpi-card__value { font-size: 28px; font-weight: 700; line-height: 1.2; margin-bottom: 4px; color: var(--text-dark); }
    .kpi-card--primary .kpi-card__value { color: white; }

    .kpi-card__label { font-size: 13px; font-weight: 600; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; }
    .kpi-card--primary .kpi-card__label { color: rgba(255, 255, 255, 0.9); }

    .kpi-card__detail { font-size: 13px; color: var(--text-lighter); margin-top: 8px; }
    .kpi-card--primary .kpi-card__detail { color: rgba(255, 255, 255, 0.85); }

    .kpi-card__progress {
        margin-top: 12px;
        height: 6px;
        background: rgba(0,0,0,0.08);
        border-radius: 3px;
        overflow: hidden;
    }
    .kpi-card--primary .kpi-card__progress { background: rgba(255,255,255,0.3); }
    .kpi-card__progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        border-radius: 3px;
        transition: width 0.4s ease;
    }
    .kpi-card--primary .kpi-card__progress-fill { background: rgba(255,255,255,0.9); }

    .kpi-grid--actions { grid-template-columns: repeat(3, 1fr); }
    @media (max-width: 900px) { .kpi-grid--actions { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .kpi-grid--actions { grid-template-columns: 1fr; } }

    .kpi-card--action { text-align: center; padding: 28px 24px; }
    .kpi-card--action .kpi-card__icon { width: 56px; height: 56px; margin: 0 auto 16px; font-size: 20px; font-weight: 700; }
    .kpi-card--action .kpi-card__value { font-size: 18px; margin-bottom: 8px; }
    .kpi-card--action .kpi-card__detail { font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
    .kpi-card--action .kpi-card__cta {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white; font-size: 14px; font-weight: 600;
        border-radius: 999px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    /* Feature Cards */
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 30px;
        width: 100%;
    }

    .feature-card {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid var(--border);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }

    .feature-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
        border-color: var(--primary);
    }

    .feature-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 12px;
        color: var(--text-dark);
    }

    .feature-description {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 20px;
        line-height: 1.6;
    }

    /* Buttons */
    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.5s, height 0.5s;
    }

    .btn:hover:not(:disabled)::before {
        width: 300px;
        height: 300px;
    }

    .btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn:active:not(:disabled) {
        transform: translateY(0);
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
    }

    .btn-primary:hover:not(:disabled) {
        box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success) 0%, var(--primary-light) 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
    }

    .btn-secondary {
        background: white;
        color: var(--text-dark);
        border: 2px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .btn-secondary:hover {
        background: var(--lighter);
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger) 0%, #ef4444 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    }

    .btn-danger:hover:not(:disabled) {
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--warning) 0%, #fbbf24 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .btn-warning:hover:not(:disabled) {
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
    }

    .btn-purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .btn-add {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 13px;
    }

    .btn-dark {
        background: linear-gradient(135deg, var(--text-dark) 0%, #374151 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(31, 41, 55, 0.3);
    }

    .btn-lg {
        padding: 16px 32px;
        font-size: 16px;
    }

    /* Button State */
    .btn.active {
        transform: scale(0.98);
    }

    /* Tables */
    .vote-table,
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }

    .vote-table th,
    .vote-table td,
    .admin-table th,
    .admin-table td {
        padding: 16px 20px;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    .vote-table th,
    .admin-table th {
        background: linear-gradient(135deg, #f3f4f6 0%, #f9fafb 100%);
        font-weight: 700;
        color: var(--text-dark);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--primary);
    }

    .vote-table td,
    .admin-table td {
        font-size: 15px;
        color: var(--text-light);
    }

    .vote-table tr:not(:last-child):hover,
    .admin-table tr:not(:last-child):hover {
        background: var(--lighter);
        transition: background-color 0.2s ease;
    }

    .vote-table tbody tr:last-child td,
    .admin-table tbody tr:last-child td {
        border-bottom: none;
    }

    .voted-status {
        color: var(--success);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: rgba(5, 150, 105, 0.1);
        border-radius: 20px;
        font-size: 13px;
    }

    /* Announcements */
    .announcements-section {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        width: 100%;
        border: 1px solid var(--border);
        transition: var(--transition);
    }

    .announcements-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border);
    }

    .announcements-icon {
        font-size: 28px;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        border-radius: 12px;
        color: white;
    }

    .announcements-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .announcement-item {
        padding: 20px;
        border-left: 4px solid;
        margin-bottom: 15px;
        background-color: var(--lighter);
        border-radius: 10px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .announcement-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .announcement-item:nth-child(1) {
        border-left-color: #0366d6;
        background: linear-gradient(135deg, rgba(3, 102, 214, 0.05) 0%, rgba(3, 102, 214, 0.02) 100%);
    }

    .announcement-item:nth-child(2) {
        border-left-color: var(--danger);
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.05) 0%, rgba(220, 38, 38, 0.02) 100%);
    }

    .announcement-item:nth-child(3) {
        border-left-color: var(--success);
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.05) 0%, rgba(5, 150, 105, 0.02) 100%);
    }

    .announcement-item:nth-child(4) {
        border-left-color: var(--warning);
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, rgba(245, 158, 11, 0.02) 100%);
    }

    .announcement-text {
        font-size: 14px;
        color: var(--text-dark);
        line-height: 1.6;
        margin: 0;
        font-weight: 500;
    }

    /* Forms */
    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 12px;
        color: var(--text-dark);
        font-size: 15px;
        letter-spacing: 0.3px;
    }

    .form-control,
    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 15px;
        background: white;
        transition: var(--transition);
        font-family: inherit;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .form-control:focus,
    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1), 0 4px 12px rgba(5, 150, 105, 0.15);
    }

    .form-control::placeholder,
    .form-input::placeholder {
        color: var(--text-lighter);
    }

    .form-group {
        margin-bottom: 24px;
        display: flex;
        flex-direction: column;
    }

    .form-text {
        font-size: 12px;
        color: var(--text-light);
        margin-top: 6px;
    }

    .form-error {
        color: var(--danger);
        font-size: 13px;
        margin-top: 6px;
        font-weight: 500;
    }

    textarea.form-control,
    textarea.form-input {
        resize: vertical;
        min-height: 120px;
    }

    select.form-control,
    select.form-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23059669' d='M10.293 3.293L6 7.586 1.707 3.293A1 1 0 00.293 4.707l6 6a1 1 0 001.414 0l6-6a1 1 0 10-1.414-1.414z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    /* Profile */
    .profile-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        padding: 40px;
        border-radius: 20px;
        color: white;
        margin-bottom: 30px;
        width: 100%;
        box-shadow: 0 8px 30px rgba(5, 150, 105, 0.3);
        position: relative;
        overflow: hidden;
    }

    .profile-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .profile-info {
        margin-bottom: 20px;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .profile-label {
        opacity: 0.9;
        font-weight: 700;
        min-width: 120px;
        letter-spacing: 0.5px;
    }

    .profile-actions {
        display: flex;
        justify-content: flex-start;
        gap: 12px;
        margin-top: 30px;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

    .profile-footer {
        text-align: center;
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
        margin-top: 20px;
        position: relative;
        z-index: 1;
    }

    /* Empty State */
    .empty-state-container {
        text-align: center;
        padding: 60px 40px;
        background: white;
        border-radius: 16px;
        border: 2px dashed var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: var(--transition);
    }

    .empty-state-container:hover {
        border-color: var(--primary);
        box-shadow: 0 8px 16px rgba(5, 150, 105, 0.1);
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.7;
        display: block;
    }

    .empty-state-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--text-dark);
    }

    .empty-state-text {
        font-size: 16px;
        color: var(--text-light);
        margin-bottom: 24px;
        line-height: 1.6;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .badge-success {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.15) 0%, rgba(5, 150, 105, 0.08) 100%);
        color: var(--success);
        border: 1px solid rgba(5, 150, 105, 0.3);
    }

    .badge-warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.08) 100%);
        color: var(--warning);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .badge-danger {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.15) 0%, rgba(220, 38, 38, 0.08) 100%);
        color: var(--danger);
        border: 1px solid rgba(220, 38, 38, 0.3);
    }

    .badge-info {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0.08) 100%);
        color: var(--info);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    /* Voted Status */
    .voted-status {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.15) 0%, rgba(5, 150, 105, 0.08) 100%);
        color: var(--success);
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid rgba(5, 150, 105, 0.3);
    }

    /* Utility Classes */
    .text-center {
        text-align: center;
    }

    .text-muted {
        color: var(--text-light);
    }

    .mt-0 {
        margin-top: 0;
    }

    .mt-1 {
        margin-top: 0.25rem;
    }

    .mt-2 {
        margin-top: 0.5rem;
    }

    .mt-3 {
        margin-top: 0.75rem;
    }

    .mt-4 {
        margin-top: 1rem;
    }

    .mt-5 {
        margin-top: 1.5rem;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .mb-1 {
        margin-bottom: 0.25rem;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-3 {
        margin-bottom: 0.75rem;
    }

    .mb-4 {
        margin-bottom: 1rem;
    }

    .mb-5 {
        margin-bottom: 1.5rem;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .gap-3 {
        gap: 0.75rem;
    }

    .gap-4 {
        gap: 1rem;
    }

    /* Container */
    .container-fluid {
        width: 100%;
        padding-left: 0;
        padding-right: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    /* Modal improvements */
    .modal-overlay {
        backdrop-filter: blur(5px);
    }

    .modal {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Show edited timestamp in italics */
    td .edited-time {
        font-size: 12px;
        color: #999;
        font-style: italic;
    }

    /* Welcome Section */
    .welcome-section {
        text-align: center;
        margin-bottom: 40px;
        width: 100%;
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

    /* Voting Page */
    .election-header {
        text-align: center;
        margin-bottom: 30px;
        width: 100%;
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

    .election-instruction {
        font-size: 14px;
        color: #666;
        margin-top: 10px;
    }

    .positions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 20px;
        width: 100%;
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
        flex-shrink: 0;
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

    /* Vote Summary */
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
    }

    .vote-table td {
        font-size: 15px;
        color: #333;
    }

    .voted-status {
        color: #059669;
        font-weight: 600;
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
        width: 100%;
    }

    .results-meta {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-top: 15px;
        font-size: 14px;
        color: #666;
        flex-wrap: wrap;
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
        width: 100%;
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
        margin-bottom: 8px;
    }

    .progress-bar-container {
        width: 100%;
        height: 8px;
        background-color: #e8e8e8;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .progress-bar.first {
        background: linear-gradient(90deg, #059669, #10b981);
    }

    .progress-bar.second {
        background-color: #6c757d;
    }

    .progress-bar.third {
        background-color: #6c757d;
    }

    /* Real-Time Election Updates Styles */
    #total-votes {
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 16px;
        color: #333;
    }

    #total-votes.updating {
        color: #059669;
        transform: scale(1.15);
    }

    #last-updated {
        font-weight: 600;
        color: #333;
        transition: color 0.3s ease;
    }

    #election-status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    #election-status-badge.active {
        background-color: #059669;
        color: white;
    }

    #election-status-badge.ended {
        background-color: #6c757d;
        color: white;
    }

    #live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        color: #059669;
        margin-left: 8px;
    }

    .live-dot {
        width: 6px;
        height: 6px;
        background-color: #059669;
        border-radius: 50%;
        animation: pulse-live 1.5s infinite;
    }

    @keyframes pulse-live {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }
    }

    /* Vote count animation */
    .vote-count {
        transition: all 0.4s ease;
        font-weight: 600;
    }

    .percentage {
        font-weight: 600;
        color: #059669;
    }

    /* Candidate result hover effect */
    .candidate-result {
        transition: transform 0.2s ease, background-color 0.2s ease;
        border-radius: 4px;
    }

    .candidate-result:hover {
        transform: translateX(4px);
        background-color: #f9f9f9;
    }

    /* Results container fade in */
    #results-container {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Profile Page */
    .profile-card {
        background: linear-gradient(135deg, #047857, #059669);
        padding: 40px;
        border-radius: 8px;
        color: white;
        margin-bottom: 30px;
        width: 100%;
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
        flex-wrap: wrap;
        gap: 10px;
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

    .profile-footer {
        text-align: center;
        color: #666;
        font-size: 14px;
    }

    .profile-footer a {
        color: #333;
        text-decoration: none;
    }

    /* Empty State */
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

    /* Admin Tables */
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

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-success {
        background-color: #ecfdf3;
        color: #047857;
    }

    /* Forms */
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
        border-color: #059669;
    }

    .election-form-section {
        background: #e8e8e8;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 20px;
        width: 100%;
    }

    .position-section {
        background: white;
        padding: 20px;
        border-radius: 6px;
        border-left: 4px solid #059669;
        margin-bottom: 15px;
    }

    .candidate-input-group {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 30px;
        flex-wrap: wrap;
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

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }

        .main-content {
            margin-left: 200px;
            width: calc(100% - 200px);
        }

        .content-area {
            padding: 20px;
        }

        .positions-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid,
        .feature-grid {
            grid-template-columns: 1fr;
        }
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

    /* RESPONSIVE DESIGN */
    @media (max-width: 1024px) {
        .content-area {
            padding: 30px;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }

        .main-content {
            margin-left: 200px;
            width: calc(100% - 200px);
        }

        .content-area {
            padding: 20px;
        }

        .top-bar {
            padding: 12px 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .search-box {
            width: 100%;
            max-width: 100%;
        }

        .user-profile .user-info {
            display: none;
        }

        .page-title { font-size: 22px; }
    }

        .stats-grid,
        .feature-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        h1 {
            font-size: 2rem;
        }

        h2 {
            font-size: 1.5rem;
        }

        h3 {
            font-size: 1.25rem;
        }

        .btn {
            padding: 10px 16px;
            font-size: 13px;
        }

        .card {
            padding: 20px;
        }

        .login-card {
            padding: 30px;
        }

        .profile-card {
            padding: 25px;
        }

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

    @media (max-width: 640px) {
        .sidebar {
            width: 160px;
        }

        .main-content {
            margin-left: 160px;
            width: calc(100% - 160px);
        }

        .sidebar-logo {
            font-size: 18px;
        }

        .nav-item {
            padding: 12px 16px;
            font-size: 13px;
        }

        .content-area {
            padding: 15px;
        }

        .top-bar-title {
            font-size: 16px;
        }

        h1 {
            font-size: 1.75rem;
        }

        h2 {
            font-size: 1.25rem;
        }

        h3 {
            font-size: 1.125rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .vote-table {
            font-size: 13px;
        }

        .vote-table th,
        .vote-table td {
            padding: 12px;
        }

        .profile-actions {
            flex-direction: column;
        }

        .profile-actions .btn {
            width: 100%;
        }

        .login-card {
            padding: 20px;
            margin: 10px;
        }

        .card {
            padding: 16px;
        }
    }
</style>
