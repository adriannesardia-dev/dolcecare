@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">My Profile</h4>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body p-4">
                <div class="mb-3 position-relative d-inline-block">
                    <img src="{{ auth()->user()->profile_picture_url }}"
                         alt="Profile"
                         class="rounded-circle border border-2"
                         width="120" height="120"
                         style="object-fit:cover;border-color:#e5e7eb!important;transition:transform .3s;"
                         id="avatarPreview">
                    <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle d-flex align-items-center justify-content-center"
                         style="width:32px;height:32px;border:3px solid #fff;cursor:pointer;"
                         onclick="document.getElementById('profile_picture').click();">
                        <i class="bi bi-camera-fill text-white" style="font-size:.75rem;"></i>
                    </div>
                </div>
                <h5>{{ auth()->user()->name }}</h5>
                <p class="text-muted mb-1">{{ auth()->user()->email }}</p>
                <p class="text-muted small mb-0">{{ auth()->user()->address ?? 'No address set' }}</p>
                <p class="text-muted small">{{ auth()->user()->gender ?? 'No gender set' }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="mb-3">Edit Profile</h5>
                <form id="profileForm" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ auth()->user()->address }}">
                            <div class="invalid-feedback" id="addressError"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">Select</option>
                                <option value="Male" {{ auth()->user()->gender === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ auth()->user()->gender === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ auth()->user()->gender === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <div class="invalid-feedback" id="genderError"></div>
                        </div>
                        <div class="col-12">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" style="display:none;">
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('profile_picture').click();">
                                    <i class="bi bi-upload"></i> Choose Image
                                </button>
                                <span class="text-muted small" id="fileName">No file chosen</span>
                            </div>
                            <div class="form-text mt-2">Accepted: jpeg, png, jpg, gif, webp. Max 2MB.</div>
                            <div class="invalid-feedback" id="profile_pictureError"></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-check-lg"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('profile_picture').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            document.getElementById('fileName').textContent = file.name;
            const reader = new FileReader();
            reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('profileForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';

        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        try {
            const res = await fetch('{{ route("profile.update") }}', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
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
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Save Changes';
    });
</script>
@endpush
