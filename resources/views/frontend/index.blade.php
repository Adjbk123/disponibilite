@extends('layouts.app')

@section('content')

<div class="container py-5 fade-in">

    {{-- Header --}}
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">🔎 Recherche de Formateur</h1>
        <p class="text-muted">Recherchez votre profil et mettez à jour votre disponibilité en quelques secondes</p>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Merci 🙏',
                text: "{{ session('success') }}",
                confirmButtonColor: '#0d6efd'
            });
            confetti({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 }
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Erreur ',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    {{-- Carte Recherche --}}
    <div class="card shadow-lg border-0 rounded-4 mb-5">
        <div class="card-body p-4">
            <form action="{{ route('frontend.searchFormateur') }}" method="POST">
                @csrf
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-end-0">🔎</span>
                    <input type="text"
                           name="query"
                           class="form-control border-start-0"
                           placeholder="Nom, email ou numéro..."
                           required>
                    <button class="btn btn-primary px-4 fw-semibold btn-search" type="submit">
                        Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Résultats --}}
    @isset($formateurs)
        @if($formateurs->count() > 0)
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white fw-bold">
                    Résultats de la recherche
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th> Email</th>
                                <th> Numéro</th>
                                <th> Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($formateurs as $formateur)
                                <tr>
                                    <td class="fw-semibold">{{ $formateur->nom }}</td>
                                    <td>{{ $formateur->email }}</td>
                                    <td>{{ $formateur->numero ?? 'N/A' }}</td>
                                    <td>
                                        @if($formateur->statut_modifie)
                                            <span class="badge bg-secondary">Déjà modifié ✅</span>
                                        @else
                                            <form action="{{ route('frontend.updateStatus') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="formateur_id" value="{{ $formateur->id }}">
                                                <select name="statut"
                                                        class="form-select fw-semibold"
                                                        onchange="this.form.submit()">
                                                    <option value="">-- Choisir --</option>
                                                    <option value="Disponible"> Disponible</option>
                                                    <option value="Indisponible">Indisponible</option>
                                                </select>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="alert alert-warning shadow-sm text-center">
                😕 Aucun formateur trouvé pour votre recherche
            </div>
        @endif
    @endisset

</div>

@endsection

{{-- Styles --}}
<style>
.fade-in {
    animation: fadeIn 0.8s ease-in-out;
}
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
}
.btn-search {
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.2);
}
</style>

{{-- SweetAlert + Confetti --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
