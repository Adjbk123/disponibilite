@extends('layouts.admin')
@section('title', 'Tableau de bord')
@section('content')
<div class="container py-4">
 @php
       
        $siteName = $parametres?->website_name ?? 'MAFLYT';
    @endphp

    <!-- Header -->
    <div class="mb-4 text-center">
        <h2 class="fw-bold text-gradient">Dashboard {{ $siteName }}</h2>
        <div id="clock" class="text-muted"></div>
    </div>

    <div class="row g-4">

        <!-- Disponible -->
        <div class="col-12 col-md-6">
            <div class="card glass bg-success text-white border-0">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h6 class="opacity-75">Formateurs disponibles</h6>
                        <h1 class="fw-bold counter" data-target="{{ $disponible }}">0</h1>
                    </div>

                    <i class="fas fa-user-check fa-3x"></i>

                </div>
            </div>
        </div>

        <!-- Indisponible -->
        <div class="col-12 col-md-6">
            <div class="card glass bg-danger text-white border-0">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h6 class="opacity-75">Formateurs indisponibles</h6>
                        <h1 class="fw-bold counter" data-target="{{ $indisponible }}">0</h1>
                    </div>

                    <i class="fas fa-user-times fa-3x"></i>

                </div>
            </div>
        </div>

        <!-- Graphique -->
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white fw-bold">
                    Statistiques des formateurs
                </div>
                <div class="card-body">
                    <div style="position: relative; height:260px;">
                        <canvas id="formateursChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<style>

/* Gradient titre */
.text-gradient {
    background: linear-gradient(45deg, #0d6efd, #198754);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Glass effect */
.glass {
    backdrop-filter: blur(10px);
    border-radius: 15px;
    transition: all 0.3s ease;
}

.glass:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

</style>

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// 🕒 Horloge temps réel
function updateClock() {
    const now = new Date();
    document.getElementById('clock').innerText =
        now.toLocaleDateString() + " — " + now.toLocaleTimeString();
}
setInterval(updateClock, 1000);
updateClock();

// 🔢 Animation compteur
document.querySelectorAll('.counter').forEach(counter => {
    const updateCount = () => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;

        const increment = target / 50;

        if (count < target) {
            counter.innerText = Math.ceil(count + increment);
            setTimeout(updateCount, 20);
        } else {
            counter.innerText = target;
        }
    };

    updateCount();
});

// 📊 Graphique
const total = {{ $disponible }} + {{ $indisponible }};
const pourcentage = total > 0 ? Math.round(({{ $disponible }} / total) * 100) : 0;

const centerText = {
    id: 'centerText',
    beforeDraw(chart) {
        const { width } = chart;
        const { height } = chart;
        const ctx = chart.ctx;

        ctx.restore();
        ctx.font = "bold 18px sans-serif";
        ctx.textBaseline = "middle";

        const text = pourcentage + "% dispo";
        const textX = Math.round((width - ctx.measureText(text).width) / 2);
        const textY = height / 2;

        ctx.fillStyle = "#198754";
        ctx.fillText(text, textX, textY);
        ctx.save();
    }
};

const ctx = document.getElementById('formateursChart').getContext('2d');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Disponible', 'Indisponible'],
        datasets: [{
            data: [{{ $disponible }}, {{ $indisponible }}],
            backgroundColor: ['#198754', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        cutout: '70%',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    },
    plugins: [centerText]
});

</script>
@endsection
