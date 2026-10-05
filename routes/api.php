<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    // Inscription
    Route::post(
        '/register',
        [AuthController::class, 'register']
    );

    // Vérification de l'OTP
    Route::post(
        '/verify-otp',
        [AuthController::class, 'verifyOtp']
    );

    // Connexion
    Route::post(
        '/login',
        [AuthController::class, 'login']
    );

    // Demande de réinitialisation du mot de passe
    Route::post(
        '/forgot-password',
        [AuthController::class, 'forgotPassword']
    );

    // Réinitialisation du mot de passe
    Route::post(
        '/reset-password',
        [AuthController::class, 'resetPassword']
    );

    /*
    |--------------------------------------------------------------------------
    | Routes protégées
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        // Profil
        Route::get(
            '/profile',
            [AuthController::class, 'profile']
        );

        // Modification du profil
        Route::put(
            '/profile-update',
            [AuthController::class, 'updateProfile']
        );

        // Déconnexion
        Route::post(
            '/logout',
            [AuthController::class, 'logOut']
        );
    });
});
