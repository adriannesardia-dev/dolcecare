<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --brand-from: #6366f1;
            --brand-to: #8b5cf6;
            --brand-gradient: linear-gradient(135deg, var(--brand-from), var(--brand-to));
        }
        * { font-family: 'Inter', sans-serif; }
        body {
            background: #f8f9fc;
            overflow-x: hidden;
            background-image:
                radial-gradient(ellipse at 15% 20%, rgba(99,102,241,.06) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 60%, rgba(139,92,246,.04) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(99,102,241,.03) 0%, transparent 50%);
            background-attachment: fixed;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #c4c4c4; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #a0a0a0; }

        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0; width: 260px;
            background: linear-gradient(180deg, #0f0c29 0%, #1e1b4b 35%, #312e81 70%, #4338ca 100%);
            color: #c7d2fe; z-index: 1040; overflow: hidden;
            transition: transform .35s cubic-bezier(.4,0,.2,1);
        }
        .sidebar::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 0% 20%, rgba(99,102,241,.12) 0%, transparent 50%),
                        radial-gradient(circle at 100% 80%, rgba(139,92,246,.08) 0%, transparent 50%);
            pointer-events: none;
        }
        .sidebar-brand {
            padding: 1.5rem 1.5rem 1rem;
            font-size: 1.25rem; font-weight: 800;
            display: flex; align-items: center; gap: .75rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
            letter-spacing: -.02em;
            position: relative;
        }
        .sidebar-brand-text {
            background: linear-gradient(135deg, #a5b4fc, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(99,102,241,.3);
        }
        .sidebar .nav-link, .logout-btn {
            color: #a5b4fc; padding: .7rem 1.25rem; margin: .1rem .75rem;
            display: flex; align-items: center; gap: .75rem; border-radius: 10px;
            transition: all .25s cubic-bezier(.4,0,.2,1); font-size: .9rem; font-weight: 500;
            position: relative; text-decoration: none;
        }
        .sidebar .nav-link i, .logout-btn i { font-size: 1.15rem; width: 1.25rem; text-align: center; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff; background: rgba(255,255,255,.1);
        }
        .sidebar .nav-link.active::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 20px; border-radius: 0 3px 3px 0;
            background: linear-gradient(180deg, #6366f1, #8b5cf6);
        }
        .logout-btn {
            background: none; border: none; width: 100%; text-align: left;
            color: #fca5a5;
        }
        .logout-btn:hover { color: #fff; background: rgba(239,68,68,.15); }
        .sidebar-divider {
            margin: .5rem .75rem; border-color: rgba(255,255,255,.06);
        }

        .sidebar-backdrop {
            position: fixed; inset: 0; background: rgba(0,0,0,.4);
            z-index: 1039; opacity: 0; pointer-events: none;
            transition: opacity .35s ease;
            backdrop-filter: blur(4px);
        }
        .sidebar-backdrop.show { opacity: 1; pointer-events: auto; }

        .main-content { margin-left: 260px; min-height: 100vh; transition: margin-left .35s cubic-bezier(.4,0,.2,1); }
        .topbar {
            background: rgba(255,255,255,.75); backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(229,231,235,.5);
            padding: .65rem 1.5rem; display: flex; align-items: center;
            justify-content: flex-end; gap: 1rem; position: sticky; top: 0; z-index: 1020;
        }
        .topbar .user-info {
            font-size: .85rem; color: #6b7280;
            display: flex; align-items: center; gap: .5rem;
            font-weight: 500;
        }
        .topbar .user-avatar {
            width: 34px; height: 34px; border-radius: 50%; object-fit: cover;
            border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,.08);
            transition: transform .2s;
        }
        .topbar .user-avatar:hover { transform: scale(1.1); }
        .page-content {
            padding: 1.5rem;
            animation: pageIn .4s cubic-bezier(.4,0,.2,1);
        }
        .sidebar-toggler {
            display: none;
            border: none; background: rgba(99,102,241,.1); color: #4f46e5;
            width: 36px; height: 36px; border-radius: 10px;
            transition: all .2s;
        }
        .sidebar-toggler:hover { background: rgba(99,102,241,.2); }

        .card {
            border: none; border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
            transition: all .3s cubic-bezier(.4,0,.2,1);
        }
        .card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.06); }
        .table { --bs-table-hover-bg: #f8fafc; }
        .table thead th {
            font-weight: 600; font-size: .75rem; text-transform: uppercase; letter-spacing: .05em;
            color: #9ca3af; border-bottom-width: 1px; padding-top: .75rem; padding-bottom: .75rem;
        }
        .table tbody tr {
            transition: all .2s;
        }
        .table tbody tr:hover { background: #f8fafc; }

        .btn {
            border-radius: 10px; font-weight: 600; padding: .5rem 1.15rem;
            transition: all .25s cubic-bezier(.4,0,.2,1); font-size: .875rem;
            position: relative; overflow: hidden;
        }
        .btn-sm { padding: .35rem .75rem; border-radius: 8px; font-size: .8rem; }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none; color: #fff;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,.35);
        }
        .btn-outline-primary { color: #6366f1; border-color: #6366f1; }
        .btn-outline-primary:hover { background: #6366f1; border-color: #6366f1; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,.2); }
        .btn-outline-danger:hover { transform: translateY(-1px); }
        .btn-secondary { background: #f3f4f6; border-color: #f3f4f6; color: #374151; }
        .btn-secondary:hover { background: #e5e7eb; border-color: #e5e7eb; }

        .form-control, .form-select {
            border-radius: 10px; border: 1.5px solid #e5e7eb;
            padding: .55rem .85rem; font-size: .9rem;
            transition: all .25s cubic-bezier(.4,0,.2,1);
            background: #fff;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12), 0 1px 2px rgba(0,0,0,.02);
        }
        .form-label { font-weight: 600; font-size: .825rem; color: #374151; margin-bottom: .35rem; }
        .form-text { font-size: .8rem; color: #9ca3af; }

        .stat-card {
            border: none; border-radius: 16px; padding: 1.5rem;
            background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.04);
            transition: all .35s cubic-bezier(.4,0,.2,1);
            position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            border-radius: 16px 16px 0 0;
            background: linear-gradient(90deg, var(--brand-from), var(--brand-to), var(--brand-from));
            background-size: 200% 100%;
            animation: shimmer 4s ease-in-out infinite;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.08); }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
            flex-shrink: 0;
        }
        h4 { font-weight: 700; color: #111827; letter-spacing: -.02em; }
        h5 { font-weight: 700; color: #111827; letter-spacing: -.02em; }
        h6 { font-weight: 600; color: #111827; }

        .toast-container { z-index: 1060; }
        .toast {
            border-radius: 12px; border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,.12);
            animation: toastIn .4s cubic-bezier(.4,0,.2,1);
        }

        .modal-content {
            border: none; border-radius: 20px;
            box-shadow: 0 24px 80px rgba(0,0,0,.2);
        }
        .modal.show .modal-dialog {
            animation: modalIn .3s cubic-bezier(.4,0,.2,1);
        }
        .modal-header { border-bottom: none; padding: 1.5rem 1.5rem 0; }
        .modal-body { padding: 1rem 1.5rem; }
        .modal-footer { border-top: none; padding: 0 1.5rem 1.5rem; }

        .page-transition {
            animation: pageIn .45s cubic-bezier(.4,0,.2,1);
        }

        @keyframes pageIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes toastIn {
            from { opacity: 0; transform: translateX(100%) scale(.9); }
            to { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(.95) translateY(-10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .animate-fade-in { animation: fadeIn .5s ease both; }
        .animate-slide-up { animation: slideUp .5s cubic-bezier(.4,0,.2,1) both; }
        .animate-pulse-slow { animation: pulse 3s ease-in-out infinite; }

        .stagger-1 { animation-delay: .05s; }
        .stagger-2 { animation-delay: .1s; }
        .stagger-3 { animation-delay: .15s; }
        .stagger-4 { animation-delay: .2s; }
        .stagger-5 { animation-delay: .25s; }
        .stagger-6 { animation-delay: .3s; }

        .btn-loading {
            pointer-events: none; opacity: .8;
        }

        .badge-count {
            position: absolute; top: -4px; right: -4px;
            width: 18px; height: 18px; border-radius: 50%;
            background: #ef4444; color: #fff; font-size: .6rem;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }

        @media (max-width: 767.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggler { display: inline-flex; align-items: center; justify-content: center; }
            .page-content { padding: 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    @auth
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon"><i class="bi bi-hospital-fill"></i></span>
            <span class="sidebar-brand-text">{{ config('app.name', 'Laravel') }}</span>
        </div>
        <hr class="sidebar-divider">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-people"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                    <i class="bi bi-file-earmark-medical"></i> Patients
                </a>
            </li>
        </ul>
        <hr class="sidebar-divider">
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                    <i class="bi bi-person-circle"></i> Profile
                </a>
            </li>
        </ul>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </aside>

    <div class="main-content" id="mainContent">
        <header class="topbar">
            <button class="sidebar-toggler me-auto" type="button" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="user-info">
                <img src="{{ auth()->user()->profile_picture_url }}" alt="" class="user-avatar">
                {{ Auth::user()->name }}
            </div>
        </header>
        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarBackdrop').classList.toggle('show');
        }
        document.getElementById('sidebar').addEventListener('transitionend', function() {
            if (!this.classList.contains('show')) {
                document.getElementById('sidebarBackdrop').classList.remove('show');
            }
        });
    </script>
    @endauth

    @guest
    <main class="container py-4">
        @yield('content')
    </main>
    @endguest

    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Confirm Delete</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="deleteModalName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="deleteModalConfirm">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            container.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.table tbody tr').forEach((tr, i) => {
                tr.style.animation = `slideUp .35s ease both`;
                tr.style.animationDelay = `${i * .04}s`;
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
