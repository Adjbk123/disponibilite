@extends('layouts.admin')
@section('title', 'affichage')
@section('content')
<div class="container py-4">

    <h2 class="mb-4">Gestion des Formateurs</h2>

    <!-- Boutons PDF / Ajouter / Réinitialiser -->
    <div class="mb-3">
        <a href="{{ route('employer.gestformateurs.formateurs.download.pdf') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> PDF
        </a>
        <a href="{{ route('employer.gestformateurs.formateurs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter
        </a>
        <button class="btn btn-warning" onclick="resetStatus()">
            <i class="fas fa-redo"></i> Réinitialiser
        </button>
    </div>

   

    <!-- Tableau -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle" id="formateurs-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Numéro</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($formateurs as $f)
                <tr data-id="{{ $f->id }}">
                    <td>{{ $f->nom }}</td>
                    <td>{{ $f->email }}</td>
                    <td>{{ $f->numero }}</td>
                    <td class="statut-cell">
                        @php
                            $badgeClass = 'badge bg-secondary'; // par défaut gris
                            if($f->statut === 'disponible') $badgeClass = 'badge bg-success';
                            elseif($f->statut === 'indisponible') $badgeClass = 'badge bg-danger';
                        @endphp
                        <span class="{{ $badgeClass }}">
                            {{ $f->statut ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('employer.gestformateurs.formateurs.edit', $f->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('employer.gestformateurs.formateurs.destroy', $f->id) }}" method="POST" class="d-inline btn-delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-delete" data-name="{{ $f->nom }}">
                                <i class="fas fa-trash-alt"></i> 
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert et Ajax -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Confirmation suppression
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e){
            e.preventDefault();
            const form = this.closest('form');
            const nom = this.getAttribute('data-name');
            Swal.fire({
                title: `Supprimer "${nom}" ?`,
                text: "Cette action est irréversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then(result => {
                if(result.isConfirmed){
                    form.submit();
                }
            });
        });
    });

    // Réinitialisation statuts
    window.resetStatus = function(){
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Tous les statuts seront réinitialisés et les formateurs pourront les mettre à jour à nouveau.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, réinitialiser !'
        }).then(result => {
            if(result.isConfirmed){
                fetch("{{ route('employer.gestformateurs.formateurs.resetStatus') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json())
                .then(data => {
                    // mettre à jour visuellement tous les badges
                    document.querySelectorAll('#formateurs-table tbody .statut-cell span').forEach(badge => {
                        badge.textContent = '—';
                        badge.className = 'badge bg-secondary';
                    });

                    // mettre à jour les compteurs
                    document.getElementById('disponible-count').textContent = 0;
                    document.getElementById('indisponible-count').textContent = 0;

                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: data.message,
                        timer: 2500,
                        showConfirmButton: false
                    });
                }).catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Impossible de réinitialiser les statuts',
                    });
                });
            }
        });
    }
});
</script>
@endsection
