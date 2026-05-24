@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Users</h4>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add User
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="text-muted">{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                            <td class="text-end">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger" data-delete="{{ $user->id }}" data-name="{{ $user->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No users found.</td>
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

        const userId = deleteTarget.dataset.delete;
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('users') }}/${userId}`;

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                deleteTarget.closest('tr').remove();
            } else {
                showToast(data.message || 'Delete failed.', 'danger');
            }
        })
        .catch(() => showToast('Something went wrong.', 'danger'));
    });
</script>
@endpush
