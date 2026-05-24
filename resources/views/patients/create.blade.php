@extends('layouts.app')

@section('title', 'Add Patient')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Add Patient</h4>
    <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form id="patientForm">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required autofocus>
                    <div class="invalid-feedback" id="first_nameError"></div>
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                    <div class="invalid-feedback" id="last_nameError"></div>
                </div>
                <div class="col-md-4">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                    <div class="invalid-feedback" id="date_of_birthError"></div>
                </div>
                <div class="col-md-4">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option value="">Select</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                    <div class="invalid-feedback" id="genderError"></div>
                </div>
                <div class="col-md-4">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                    <div class="invalid-feedback" id="contact_numberError"></div>
                </div>
                <div class="col-12">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                    <div class="invalid-feedback" id="addressError"></div>
                </div>
                <div class="col-12">
                    <label for="medical_history" class="form-label">Medical History</label>
                    <textarea class="form-control" id="medical_history" name="medical_history" rows="3"></textarea>
                    <div class="invalid-feedback" id="medical_historyError"></div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-check-lg"></i> Save Patient
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('patientForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';

        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        try {
            const res = await fetch('{{ route("patients.store") }}', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(this),
            });

            const data = await res.json();

            if (data.success) {
                showToast(data.message, 'success');
                this.reset();
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
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Save Patient';
    });
</script>
@endpush
