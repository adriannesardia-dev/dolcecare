@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Dashboard</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Welcome back, <strong>{{ Auth::user()->name }}</strong></p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#eef2ff;color:#4f46e5;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <span class="text-muted" style="font-size:.8rem;font-weight:500;">Total Users</span>
                <h3 class="mb-0 mt-1" style="font-weight:700;">{{ $totalUsers }}</h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#ecfdf5;color:#059669;">
                <i class="bi bi-file-earmark-medical-fill"></i>
            </div>
            <div>
                <span class="text-muted" style="font-size:.8rem;font-weight:500;">Total Patients</span>
                <h3 class="mb-0 mt-1" style="font-weight:700;">{{ $totalPatients }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-bar-chart-fill me-2" style="color:#4f46e5;"></i>System Overview</h6>
                <canvas id="overviewChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-pie-chart-fill me-2" style="color:#059669;"></i>Records Distribution</h6>
                <canvas id="distributionChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const totalUsers = {{ $totalUsers }};
    const totalPatients = {{ $totalPatients }};

    new Chart(document.getElementById('overviewChart'), {
        type: 'bar',
        data: {
            labels: ['Users', 'Patients'],
            datasets: [{
                label: 'Total Records',
                data: [totalUsers, totalPatients],
                backgroundColor: ['#4f46e5', '#059669'],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    new Chart(document.getElementById('distributionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Users', 'Patients'],
            datasets: [{
                data: [totalUsers, totalPatients],
                backgroundColor: ['#4f46e5', '#059669'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { font: { family: 'Inter' } } } }
        }
    });
</script>
@endpush
