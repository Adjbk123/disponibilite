<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formateur extends Model
{
    // Table associée
    protected $table = 'formateurs';

    // Champs autorisés pour la création ou la mise à jour (mass assignment)
    protected $fillable = [
        'nom',
        'email',
        'numero',
        'statut',
        'statut_modifie',
    ];
}
