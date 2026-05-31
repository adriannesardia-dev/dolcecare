@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit User</h4>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form id="userForm">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required autofocus>
                    <div class="invalid-feedback" id="nameError"></div>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                    <div class="invalid-feedback" id="emailError"></div>
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">New Password <span class="text-muted fw-normal">(leave blank to keep current)</span></label>
                    <input type="password" class="form-control" id="password" name="password" minlength="8">
                    <div class="invalid-feedback" id="passwordError"></div>
                </div>
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-check-lg"></i> Update User
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('userForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        if (passwordConfirmation && !password) {
            showToast('Please enter a new password to change it.', 'danger');
            document.getElementById('submitBtn').disabled = false;
            return;
        }

        if (password && !passwordConfirmation) {
            showToast('Please confirm your new password.', 'danger');
            document.getElementById('submitBtn').disabled = false;
            return;
        }

        if (password && passwordConfirmation && password !== passwordConfirmation) {
            showToast('Passwords do not match.', 'danger');
            document.getElementById('submitBtn').disabled = false;
            return;
        }

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Updating...';

        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        try {
            const res = await fetch('{{ route("users.update", $user) }}', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(this),
            });

            const data = await res.json();

            if (data.success) {
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Something went wrong.', 'danger');
            }
        } catch (err) {
            try {
                const data = await err.json();
                if (data.errors) {
                    for (const [field, messages] of Object.entries(data.errors)) {
                        const input = document.getElementById(field);
                        if (input) {
                            input.classList.add('is-invalid');
                            document.getElementById(field + 'Error').textContent = messages[0];
                        }
                    }
                } else {
                    showToast(data.message || 'Something went wrong.', 'danger');
                }
            } catch {
                showToast('Something went wrong.', 'danger');
            }
        }

        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Update User';
    });
</script>
@endpush
