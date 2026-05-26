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
    <div class="col-sm-6 animate-slide-up stagger-1">
        <div class="stat-card d-flex align-items-center gap-3" style="--stat-color: #6366f1;">
            <div class="stat-icon" style="background:linear-gradient(135deg,#eef2ff,#e0e7ff);color:#4f46e5;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <span class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.03em;">Total Users</span>
                <h3 class="mb-0 mt-1 fw-bold" id="userCount">{{ $totalUsers }}</h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 animate-slide-up stagger-2">
        <div class="stat-card d-flex align-items-center gap-3" style="--stat-color: #059669;">
            <div class="stat-icon" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);color:#059669;">
                <i class="bi bi-file-earmark-medical-fill"></i>
            </div>
            <div>
                <span class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.03em;">Total Patients</span>
                <h3 class="mb-0 mt-1 fw-bold" id="patientCount">{{ $totalPatients }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6 animate-slide-up stagger-3">
        <div class="card border-0">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-bar-chart-fill me-2" style="color:#4f46e5;"></i>System Overview</h6>
                <canvas id="overviewChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 animate-slide-up stagger-4">
        <div class="card border-0">
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

    function animateCounter(el, target, duration = 800) {
        const start = performance.now();
        function update(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target);
            if (progress < 1) requestAnimationFrame(update);
            else el.textContent = target;
        }
        requestAnimationFrame(update);
    }

    setTimeout(() => {
        animateCounter(document.getElementById('userCount'), totalUsers);
        animateCounter(document.getElementById('patientCount'), totalPatients);
    }, 300);

    new Chart(document.getElementById('overviewChart'), {
        type: 'bar',
        data: {
            labels: ['Users', 'Patients'],
            datasets: [{
                label: 'Total Records',
                data: [totalUsers, totalPatients],
                backgroundColor: ['#6366f1', '#059669'],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter' } }, grid: { color: 'rgba(0,0,0,.04)' } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('distributionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Users', 'Patients'],
            datasets: [{
                data: [totalUsers, totalPatients],
                backgroundColor: ['#6366f1', '#059669'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 12 }, padding: 16 } }
            },
            cutout: '70%',
        },
        plugins: [{
            id: 'gradientArcs',
            beforeDraw(chart) {
                const { ctx, chartArea, data } = chart;
                if (!chartArea) return;
                const cx = chartArea.left + (chartArea.right - chartArea.left) / 2;
                const cy = chartArea.top + (chartArea.bottom - chartArea.top) / 2;
                const outerR = Math.min(chartArea.right - chartArea.left, chartArea.bottom - chartArea.top) / 2;
                const g1 = ctx.createConicGradient(0, cx, cy);
                g1.addColorStop(0, 'rgba(99,102,241,1)');
                g1.addColorStop(0.35, 'rgba(99,102,241,1)');
                g1.addColorStop(0.5, 'rgba(5,150,105,1)');
                g1.addColorStop(0.85, 'rgba(5,150,105,1)');
                g1.addColorStop(1, 'rgba(99,102,241,1)');
                data.datasets[0].backgroundColor = g1;
            }
        }]
    });
</script>
@endpush
