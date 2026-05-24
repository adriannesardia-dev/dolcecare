<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f0f2f5; }

        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0; width: 260px;
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
            color: #c7d2fe; z-index: 1000; overflow: hidden;
            transition: transform .3s;
        }
        .sidebar-brand {
            padding: 1.5rem 1.5rem 1rem;
            font-size: 1.2rem; font-weight: 700; color: #fff;
            display: flex; align-items: center; gap: .6rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-brand .brand-icon {
            width: 32px; height: 32px;
            background: rgba(255,255,255,.15);
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
        }
        .sidebar .nav-link, .logout-btn {
            color: #a5b4fc; padding: .65rem 1.25rem; margin: .15rem .75rem;
            display: flex; align-items: center; gap: .75rem; border-radius: 8px;
            transition: all .2s; font-size: .9rem; font-weight: 500;
        }
        .sidebar .nav-link i, .logout-btn i { font-size: 1.1rem; width: 1.25rem; text-align: center; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff; background: rgba(255,255,255,.12);
        }
        .logout-btn {
            background: none; border: none; width: 100%; text-align: left;
        }
        .logout-btn:hover { color: #fca5a5; background: rgba(255,255,255,.08); }
        .sidebar-divider {
            margin: .5rem .75rem; border-color: rgba(255,255,255,.06);
        }

        .main-content { margin-left: 260px; min-height: 100vh; }
        .topbar {
            background: rgba(255,255,255,.8); backdrop-filter: blur(12px);
            border-bottom: 1px solid #e5e7eb;
            padding: .65rem 1.5rem; display: flex; align-items: center;
            justify-content: flex-end; gap: 1rem; position: sticky; top: 0; z-index: 999;
        }
        .topbar .user-info {
            font-size: .85rem; color: #6b7280;
            display: flex; align-items: center; gap: .5rem;
        }
        .topbar .user-avatar {
            width: 32px; height: 32px; border-radius: 50%; object-fit: cover;
            border: 2px solid #e5e7eb;
        }
        .page-content { padding: 1.5rem; }
        .sidebar-toggler { display: none; }

        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.04); transition: box-shadow .2s; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.06); }
        .table { --bs-table-hover-bg: #f8fafc; }
        .table thead th { font-weight: 600; font-size: .8rem; text-transform: uppercase; letter-spacing: .03em; color: #6b7280; border-bottom-width: 1px; }
        .btn { border-radius: 8px; font-weight: 500; padding: .45rem 1rem; transition: all .2s; }
        .btn-sm { padding: .3rem .65rem; border-radius: 6px; }
        .btn-primary { background: #4f46e5; border-color: #4f46e5; }
        .btn-primary:hover { background: #4338ca; border-color: #4338ca; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(79,70,229,.25); }
        .btn-outline-primary { color: #4f46e5; border-color: #4f46e5; }
        .btn-outline-primary:hover { background: #4f46e5; border-color: #4f46e5; }
        .btn-outline-danger:hover { transform: translateY(-1px); }
        .form-control, .form-select { border-radius: 8px; border-color: #e5e7eb; padding: .5rem .75rem; font-size: .9rem; }
        .form-control:focus, .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
        .form-label { font-weight: 500; font-size: .85rem; color: #374151; margin-bottom: .35rem; }
        .stat-card {
            border: none; border-radius: 12px; padding: 1.25rem;
            background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.04);
            transition: all .25s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
        }
        h4 { font-weight: 600; color: #111827; }
        h5 { font-weight: 600; color: #111827; }
        h6 { font-weight: 600; color: #111827; }

        @media (max-width: 767.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggler { display: inline-block; }
        }
    </style>
    @stack('styles')
</head>
<body>

    @auth
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon"><i class="bi bi-hospital-fill"></i></span>
            {{ config('app.name', 'Laravel') }}
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

    <div class="main-content">
        <header class="topbar">
            <button class="btn btn-outline-secondary sidebar-toggler me-auto" type="button" onclick="document.getElementById('sidebar').classList.toggle('show')">
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
                <div class="modal-header border-0">
                    <h6 class="modal-title">Confirm Delete</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="deleteModalName"></strong>?
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
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
    </script>
    @stack('scripts')
</body>
</html>
