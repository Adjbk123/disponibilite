<?php

namespace App\Http\Controllers;

use App\Models\Formateur;
use App\Models\Parametre;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormateurController extends Controller
{
    // 🔹 Liste des formateurs
    public function index()
    {
        $formateurs = Formateur::all();
        $disponible = Formateur::where('statut', 'disponible')->count();
        $indisponible = Formateur::where('statut', 'indisponible')->count();

        return view('formateurs.index', compact('formateurs', 'disponible', 'indisponible'));
    }

    // 🔹 Télécharger PDF des formateurs disponibles
    public function downloadPdfDisponibles()
    {
        $formateurs = Formateur::where('statut', 'disponible')->get();
        $parametres = Parametre::first();
        $pdf = Pdf::loadView('formateurs.pdf_disponibles', compact('formateurs','parametres'));
        return $pdf->download('formateurs_disponibles.pdf');
    }

    // 🔹 Formulaire création
    public function create()
    {
        return view('formateurs.create');
    }

    // 🔹 Enregistrer un nouveau formateur
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom'    => 'required|string|max:255',
            'email'  => 'required|email|unique:formateurs,email',
            'numero' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Formateur::create([
            'nom'    => $request->nom,
            'email'  => $request->email,
            'numero' => $request->numero,
            // ⚠️ 'statut' n’est pas défini, donc il sera NULL par défaut
        ]);

        return redirect()->route('employer.gestformateurs.formateurs.index')
                         ->with('success', 'Formateur ajouté avec succès !');
    }

    // 🔹 Formulaire édition
    public function edit($id)
    {
        $formateur = Formateur::findOrFail($id);
        return view('formateurs.edit', compact('formateur'));
    }

    // 🔹 Mettre à jour formateur (infos ou statut)
    public function update(Request $request, $id)
    {
        $formateur = Formateur::findOrFail($id);

        // Si on modifie le statut uniquement
        if ($request->filled('statut')) {
            $request->validate([
                'statut' => 'required|in:disponible,indisponible',
            ]);

            $formateur->statut = $request->statut;
            $formateur->save();

            return redirect()->back()->with('success', 'Statut du formateur mis à jour !');
        }

        // Sinon mise à jour des infos générales
        $request->validate([
            'nom'    => 'required|string|max:255',
            'email'  => 'required|email|unique:formateurs,email,' . $id,
            'numero' => 'required|string|max:20',
        ]);

        $formateur->update([
            'nom'    => $request->nom,
            'email'  => $request->email,
            'numero' => $request->numero,
        ]);

        return redirect()->back()->with('success', 'Informations du formateur mises à jour !');
    }

    // 🔹 Supprimer un formateur
    public function destroy($id)
    {
        $formateur = Formateur::findOrFail($id);
        $formateur->delete();

        return redirect()->route('employer.gestformateurs.formateurs.index')
                         ->with('success', 'Formateur supprimé avec succès !');
    }

    // 🔹 Réinitialiser tous les statuts
    public function resetStatus()
    {
        Formateur::query()->update([
            'statut'         => null,
            'statut_modifie' => false,
        ]);

        return response()->json([
            'message' => 'Tous les statuts ont été réinitialisés !'
        ]);
    }
}
