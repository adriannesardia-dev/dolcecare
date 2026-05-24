<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh; display: flex; align-items: center; position: relative;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            overflow: hidden;
        }
        body::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 20% 50%, rgba(102,126,234,.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 50%, rgba(118,75,162,.15) 0%, transparent 50%);
        }
        .bg-icon-1, .bg-icon-2 {
            position: absolute; color: rgba(255,255,255,.03); z-index: 0;
        }
        .bg-icon-1 { font-size: 20rem; bottom: -3rem; left: -3rem; transform: rotate(-15deg); }
        .bg-icon-2 { font-size: 14rem; top: -2rem; right: -2rem; transform: rotate(20deg); }
        .login-card {
            border: none; border-radius: 20px;
            box-shadow: 0 25px 80px rgba(0,0,0,.3);
            backdrop-filter: blur(4px);
            position: relative; z-index: 1;
        }
        .login-card .card-body { padding: 2.5rem; }
        .login-card h3 { font-weight: 700; color: #111827; }
        .form-control {
            border-radius: 10px; border: 1.5px solid #e5e7eb;
            padding: .6rem .85rem; font-size: .9rem; transition: all .2s;
        }
        .form-control:focus {
            border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.15);
        }
        .form-label { font-weight: 500; font-size: .85rem; color: #374151; }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none; border-radius: 10px; padding: .65rem; font-weight: 600;
            transition: all .25s;
        }
        .btn-primary:hover {
            transform: translateY(-2px); box-shadow: 0 6px 20px rgba(102,126,234,.35);
        }
        .brand-icon {
            width: 64px; height: 64px; border-radius: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem; font-size: 1.6rem;
            box-shadow: 0 8px 24px rgba(102,126,234,.3);
        }
        a { color: #667eea; font-weight: 500; text-decoration: none; }
        a:hover { color: #764ba2; text-decoration: underline; }
    </style>
</head>
<body>
    <i class="bi bi-hospital-fill bg-icon-1"></i>
    <i class="bi bi-heart-pulse-fill bg-icon-2"></i>

    <div class="container" style="position:relative;z-index:1;">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card login-card">
                    <div class="card-body">
                        <div class="brand-icon"><i class="bi bi-hospital-fill"></i></div>
                        <h3 class="text-center mb-1">Welcome Back</h3>
                        <p class="text-center text-muted mb-4" style="font-size:.9rem;">Sign in to DOLCECARE</p>

                        <form id="loginForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="you@example.com" required autofocus>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                <div class="invalid-feedback" id="passwordError"></div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="loginBtn">Sign In</button>
                        </form>

                        <p class="text-center mt-4 mb-0" style="font-size:.9rem;">
                            Don't have an account?
                            <a href="{{ route('register') }}">Create one</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    <script>
        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('loginBtn');
            btn.disabled = true; btn.textContent = 'Signing in...';
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            try {
                const res = await fetch('{{ route("login") }}', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(this),
                });
                const data = await res.json();
                if (data.success) {
                    showToast('Login successful!', 'success');
                    setTimeout(() => window.location.href = data.redirect, 1000);
                } else {
                    showToast(data.message, 'danger');
                    btn.disabled = false; btn.textContent = 'Sign In';
                }
            } catch (err) {
                try {
                    const data = await err.json();
                    if (data.errors) {
                        for (const [field, messages] of Object.entries(data.errors)) {
                            const input = document.getElementById(field);
                            if (input) { input.classList.add('is-invalid'); document.getElementById(field + 'Error').textContent = messages[0]; }
                        }
                    } else if (data.message) { showToast(data.message, 'danger'); }
                } catch { showToast('Something went wrong.', 'danger'); }
                btn.disabled = false; btn.textContent = 'Sign In';
            }
        });
    </script>
</body>
</html>
