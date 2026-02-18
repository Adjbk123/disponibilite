<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Formateur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FrontendController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    // ⭐ PAGE recherche (GET)
    public function searchFormateurPage()
    {
        return view('frontend.index');
    }

    // ⭐ Recherche POST
    public function searchFormateur(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = $request->input('query');

        $formateurs = Formateur::where('nom', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('numero', 'like', "%{$query}%")
            ->get();

        return view('frontend.index', compact('formateurs', 'query'));
    }

    // ⭐ Mise à jour statut
    public function updateStatus(Request $request)
{
    $request->validate([
        'formateur_id' => 'required|exists:formateurs,id',
        'statut' => 'required|string|in:Disponible,Indisponible',
    ]);

    $formateur = Formateur::find($request->formateur_id);

    // 🚫 Vérifier s'il a déjà modifié
    if ($formateur->statut_modifie) {
        return redirect()->back()->with('error', 'Vous avez déjà modifié votre statut.');
    }

    // ✅ Mettre à jour
    $formateur->statut = $request->statut;
    $formateur->statut_modifie = true;
    $formateur->save();

    return redirect()->back()->with('success', 'Merci beaucoup d’être fidèle à MAFLYT 🙏');
}

 public function loginForm()
    {
        return view('frontend.pages.login');
    }

    public function loginSubmit(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Connecté avec succès !');
        }

        return back()->withErrors(['email' => 'Email ou mot de passe incorrect']);
    }

    public function registerForm()
    {
        return view('frontend.pages.register');
    }

    public function registerSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect('/')->with('success', 'Inscrit avec succès !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Déconnecté avec succès !');
    }

}
