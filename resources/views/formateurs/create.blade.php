@extends('layouts.admin')
@section('title', 'inscription')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-12">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Ajouter un Formateur</h3>
                </div>

                <div class="card-body p-5">
                    <form action="{{ route('employer.gestformateurs.formateurs.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="nom" class="form-label fw-bold">Nom</label>
                            <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" 
                                   value="{{ old('nom') }}" required autocomplete="name">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="numero" class="form-label fw-bold">Numéro de téléphone</label>
                            <input type="text" name="numero" id="numero" class="form-control @error('numero') is-invalid @enderror" 
                                   value="{{ old('numero') }}" required >
                            @error('numero')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('employer.gestformateurs.formateurs.index') }}" class="btn btn-secondary me-3">Annuler</a>
                            <button type="submit" class="btn btn-success">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Veuillez corriger les erreurs dans le formulaire.',
        });
    @endif

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: "{{ session('success') }}",
            timer: 2500,
            showConfirmButton: false
        });
    @endif
</script>
@endsection
