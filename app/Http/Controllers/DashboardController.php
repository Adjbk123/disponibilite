<?php

namespace App\Http\Controllers;

use App\Models\Formateur;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Compter les formateurs disponibles et indisponibles
        $disponible = Formateur::where('statut', 'disponible')->count();
        $indisponible = Formateur::where('statut', 'indisponible')->count();

        return view('dashboard', compact('disponible', 'indisponible'));
    }
}
