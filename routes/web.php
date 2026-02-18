<?php

use App\Http\Controllers\FrontendController;
use App\Livewire\Utilisateurs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
   //return view('welcome');
//});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::controller(App\Http\Controllers\Frontend\FrontendController::class)->group(function () {
    Route::get('/', 'index'); // Accueil
    Route::get('/frontend.login', 'loginForm')->name('frontend.login');
    Route::post('/frontend.login', 'loginSubmit')->name('login.submit');
    Route::get('/frontend.logout', 'logout')->name('frontend.logout');

    // Register
    Route::get('/frontend.register', 'registerForm')->name('frontend.register');
    Route::post('/frontend.register', 'registerSubmit')->name('register.submit');
});



Route::get('/search-formateur', [App\Http\Controllers\Frontend\FrontendController::class, 'searchFormateurPage'])
    ->name('frontend.searchFormateurPage');

Route::post('/search-formateur', [App\Http\Controllers\Frontend\FrontendController::class, 'searchFormateur'])
    ->name('frontend.searchFormateur');


Route::post('/update-status', [App\Http\Controllers\Frontend\FrontendController::class, 'updateStatus'])
    ->name('frontend.updateStatus');



Route::group([
    "middleware" => ["auth", "auth.administrateur"],
    'as' => 'administrateur.'
], function () {
    // Dashboard Admin
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::group([
        "prefix" => "gestutilisateurs",
        "as" => "gestutilisateurs."
    ], function () {
        Route::get("/utilisateurs", Utilisateurs::class)->name("users.index");
        // Route::get('/rolesetpermition', [App\Http\Controllers\UserController::class, 'index'])->name('rolespermissions.index');
    });

    Route::group([
        "prefix" => "gestparametres",
        'as' => 'gestparametres.'
    ], function () {
       Route::get('/', [App\Http\Controllers\ParametreController::class, 'index'])->name('parametres.index');
    Route::post('parametres', [App\Http\Controllers\ParametreController::class, 'store'])->name('parametres.store');
    });



    
});
Route::group([
    "middleware" => ["auth", "auth.employer"],
    'as' => 'employer.'
], function () {

    Route::group([
        "prefix" => "gestformateurs",
        'as' => 'gestformateurs.'
    ], function () {

        Route::get('/', [App\Http\Controllers\FormateurController::class, 'index'])
            ->name('formateurs.index');

        Route::get('/download-pdf', [App\Http\Controllers\FormateurController::class, 'downloadPdfDisponibles'])
            ->name('formateurs.download.pdf');

        Route::get('/create', [App\Http\Controllers\FormateurController::class, 'create'])
            ->name('formateurs.create');

        Route::post('/store', [App\Http\Controllers\FormateurController::class, 'store'])
            ->name('formateurs.store');

        Route::get('/{Formateur}/edit', [App\Http\Controllers\FormateurController::class, 'edit'])
            ->name('formateurs.edit');

        Route::put('/{Formateur}', [App\Http\Controllers\FormateurController::class, 'update'])
            ->name('formateurs.update');

        Route::delete('/{Formateur}', [App\Http\Controllers\FormateurController::class, 'destroy'])
            ->name('formateurs.destroy');

        // ✅ Réinitialisation des statuts
        Route::post('/reset-status', [App\Http\Controllers\FormateurController::class, 'resetStatus'])
            ->name('formateurs.resetStatus');

    });

});


   
