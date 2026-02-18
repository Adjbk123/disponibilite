@extends('layouts.admin')
@section('title', 'modification')
@section('content')
<div class="container py-4">

    <h2 class="mb-4 text-center text-primary fw-bold">Modifier le Formateur</h2>

    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">

            {{-- Card Informations générales --}}
            <div class="card shadow-sm mb-4 rounded-4 border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Informations générales</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employer.gestformateurs.formateurs.update', $formateur->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nom</label>
                            <input type="text" name="nom" class="form-control" value="{{ old('nom', $formateur->nom) }}" required>
                            @error('nom')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $formateur->email) }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Numéro</label>
                            <input type="text" name="numero" class="form-control" value="{{ old('numero', $formateur->numero) }}" required>
                            @error('numero')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-edit"></i> Modifier les informations
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card Statut --}}
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header bg-success text-white text-center">
                    <h5 class="mb-0">Statut du Formateur</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employer.gestformateurs.formateurs.update', $formateur->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Statut</label>
                            <select name="statut" class="form-control" required>
                                <option value="">Choisir le statut</option>
                                <option value="disponible" {{ $formateur->statut === 'disponible' ? 'selected' : '' }}>✅ Disponible</option>
                                <option value="indisponible" {{ $formateur->statut === 'indisponible' ? 'selected' : '' }}>❌ Indisponible</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-check"></i> Enregistrer le statut
                        </button>

                        <a href="{{ route('employer.gestformateurs.formateurs.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- SweetAlert --}}
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'success',
    title: 'Succès',
    text: {!! json_encode(session('success')) !!},
    timer: 2500,
    showConfirmButton: false
});
</script>
@endif

@endsection
