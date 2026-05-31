<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh; display: flex; align-items: center;
            background: linear-gradient(135deg, #0f0c29 0%, #1e1b4b 40%, #312e81 100%);
            overflow: hidden; position: relative;
        }

        .aurora-bg {
            position: absolute; inset: 0; overflow: hidden; pointer-events: none; z-index: 0;
        }
        .aurora-bg::before,
        .aurora-bg::after {
            content: ''; position: absolute; border-radius: 50%; filter: blur(80px);
            animation: auroraFloat 18s infinite ease-in-out;
        }
        .aurora-bg::before {
            width: 500px; height: 500px;
            background: rgba(99,102,241,.15);
            top: -150px; left: -150px;
        }
        .aurora-bg::after {
            width: 400px; height: 400px;
            background: rgba(139,92,246,.12);
            bottom: -100px; right: -100px;
            animation-delay: -7s;
        }
        .aurora-blob {
            position: absolute; border-radius: 50%; filter: blur(60px);
            animation: auroraFloat 22s infinite ease-in-out;
        }
        .aurora-blob:nth-child(1) {
            width: 300px; height: 300px;
            background: rgba(167,139,250,.08);
            top: 40%; left: -100px;
            animation-delay: -3s;
            animation-duration: 25s;
        }
        .aurora-blob:nth-child(2) {
            width: 200px; height: 200px;
            background: rgba(99,102,241,.06);
            bottom: 30%; right: -50px;
            animation-delay: -10s;
            animation-duration: 20s;
        }
        .aurora-blob:nth-child(3) {
            width: 350px; height: 350px;
            background: rgba(139,92,246,.05);
            top: -100px; right: 20%;
            animation-delay: -15s;
            animation-duration: 28s;
        }

        @keyframes auroraFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(40px, -50px) scale(1.1); }
            50% { transform: translate(-30px, 30px) scale(.9); }
            75% { transform: translate(30px, 40px) scale(1.05); }
        }

        .grid-overlay {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.02) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none; z-index: 0;
        }

        .auth-wrapper {
            position: relative; z-index: 1; width: 100%;
            animation: wrapperIn .8s cubic-bezier(.4,0,.2,1);
        }
        @keyframes wrapperIn {
            from { opacity: 0; transform: translateY(30px) scale(.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .auth-card {
            border: none; border-radius: 24px;
            background: rgba(255,255,255,.98);
            box-shadow: 0 25px 80px rgba(0,0,0,.35);
            overflow: hidden;
        }
        .auth-card .card-body { padding: 2.5rem; }

        .brand-icon {
            width: 72px; height: 72px; border-radius: 20px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem; font-size: 1.8rem;
            box-shadow: 0 8px 24px rgba(99,102,241,.35);
            animation: pulse-glow 3s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 8px 24px rgba(99,102,241,.35); }
            50% { box-shadow: 0 8px 32px rgba(99,102,241,.5); }
        }

        .auth-title { font-size: 1.6rem; font-weight: 800; color: #111827; letter-spacing: -.03em; }
        .auth-subtitle { font-size: .9rem; color: #9ca3af; margin-bottom: 1.5rem; }
        .brand-gradient-text {
            background: linear-gradient(135deg, #6366f1, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .form-floating-custom {
            position: relative; margin-bottom: 1.25rem;
        }
        .form-floating-custom .form-label {
            font-weight: 600; font-size: .8rem; color: #374151;
            margin-bottom: .4rem; display: block;
        }
        .form-floating-custom .input-group-custom {
            position: relative;
        }
        .form-floating-custom .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-size: 1rem; z-index: 2;
            transition: color .25s;
        }
        .form-floating-custom .form-control {
            border-radius: 12px; border: 1.5px solid #e5e7eb;
            padding: .65rem .85rem .65rem 2.5rem;
            font-size: .9rem; background: #f9fafb;
            transition: all .3s cubic-bezier(.4,0,.2,1);
        }
        .form-floating-custom .form-control:focus {
            border-color: #6366f1; background: #fff;
            box-shadow: 0 0 0 3px rgba(99,102,241,.1), 0 1px 3px rgba(0,0,0,.02);
        }
        .form-floating-custom .form-control:focus ~ .input-icon,
        .form-floating-custom .form-control.is-valid ~ .input-icon { color: #6366f1; }
        .form-floating-custom .form-control.is-invalid { border-color: #ef4444; background: #fef2f2; }
        .form-floating-custom .form-control.is-invalid ~ .input-icon { color: #ef4444; }

        .btn-auth {
            width: 100%; padding: .75rem; border-radius: 12px; font-weight: 700; font-size: .95rem;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none; color: #fff;
            transition: all .3s cubic-bezier(.4,0,.2,1);
            position: relative; overflow: hidden;
        }
        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(99,102,241,.35);
        }
        .btn-auth:active { transform: translateY(0); }

        .auth-link { color: #6366f1; font-weight: 600; text-decoration: none; transition: all .2s; }
        .auth-link:hover { color: #4f46e5; text-decoration: underline; }

        .divider-text {
            display: flex; align-items: center; gap: .75rem;
            color: #9ca3af; font-size: .8rem; margin: 1.25rem 0;
        }
        .divider-text::before, .divider-text::after {
            content: ''; flex: 1; height: 1px; background: #e5e7eb;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeSlideUp .5s cubic-bezier(.4,0,.2,1) both; }
        .delay-1 { animation-delay: .1s; }
        .delay-2 { animation-delay: .2s; }
        .delay-3 { animation-delay: .3s; }
        .delay-4 { animation-delay: .4s; }
    </style>
</head>
<body>
    <div class="aurora-bg">
        <div class="aurora-blob"></div>
        <div class="aurora-blob"></div>
        <div class="aurora-blob"></div>
    </div>
    <div class="grid-overlay"></div>

    <div class="auth-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">

                    <div class="auth-card">
                        <div class="card-body">
                            <div class="text-center animate-in">
                                <div class="brand-icon"><i class="bi bi-hospital-fill"></i></div>
                                <h3 class="auth-title">Welcome Back</h3>
                                <p class="auth-subtitle">Sign in to continue to <span class="brand-gradient-text">DOLCECARE</span></p>
                            </div>

                            <form id="loginForm" class="animate-in delay-1">
                                @csrf
                                <div class="form-floating-custom">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group-custom">
                                        <i class="bi bi-envelope-fill input-icon"></i>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="you@example.com" required autofocus>
                                    </div>
                                    <div class="invalid-feedback" id="emailError"></div>
                                </div>
                                <div class="form-floating-custom">
                                    <label class="form-label">Password</label>
                                    <div class="input-group-custom">
                                        <i class="bi bi-lock-fill input-icon"></i>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                    </div>
                                    <div class="invalid-feedback" id="passwordError"></div>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember" style="font-size:.875rem;color:#6b7280;font-weight:500;">
                                        Remember me
                                    </label>
                                </div>
                                <button type="submit" class="btn-auth" id="loginBtn">
                                    <span id="loginBtnText">Sign In</span>
                                    <span id="loginBtnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                                </button>
                            </form>

                            <div class="divider-text animate-in delay-2">or</div>

                            <p class="text-center mb-0 animate-in delay-3" style="font-size:.9rem;color:#6b7280;">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="auth-link">Create one</a>
                            </p>
                        </div>
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

        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('loginBtn');
            const btnText = document.getElementById('loginBtnText');
            const btnSpinner = document.getElementById('loginBtnSpinner');
            btn.disabled = true;
            btnText.textContent = 'Signing in...';
            btnSpinner.classList.remove('d-none');
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
                    btn.disabled = false;
                    btnText.textContent = 'Sign In';
                    btnSpinner.classList.add('d-none');
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
                btn.disabled = false;
                btnText.textContent = 'Sign In';
                btnSpinner.classList.add('d-none');
            }
        });
    </script>
</body>
</html>
