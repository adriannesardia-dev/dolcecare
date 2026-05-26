@extends('layouts.app')

@section('title', 'Patients')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Patients</h4>
    <a href="{{ route('patients.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Patient
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="patientsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Contact</th>
                        <th>Date of Birth</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($patients as $patient)
                        <tr>
                            <td class="text-muted">{{ $patient->id }}</td>
                            <td class="fw-medium">{{ $patient->first_name }} {{ $patient->last_name }}</td>
                            <td>{{ $patient->gender }}</td>
                            <td>{{ $patient->contact_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger" data-delete="{{ $patient->id }}" data-name="{{ $patient->first_name }} {{ $patient->last_name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No patient records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<form method="POST" id="deleteForm" style="display:none">
    @csrf @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    let deleteTarget = null;

    document.querySelectorAll('[data-delete]').forEach(btn => {
        btn.addEventListener('click', function () {
            deleteTarget = this;
            document.getElementById('deleteModalName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });

    document.getElementById('deleteModalConfirm').addEventListener('click', function () {
        if (!deleteTarget) return;

        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();

        const patientId = deleteTarget.dataset.delete;
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('patients') }}/${patientId}`;

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                const row = deleteTarget.closest('tr');
                row.style.transition = 'all .3s ease';
                row.style.transform = 'translateX(20px)';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            } else {
                showToast(data.message || 'Delete failed.', 'danger');
            }
        })
        .catch(() => showToast('Something went wrong.', 'danger'));
    });
</script>
@endpush
