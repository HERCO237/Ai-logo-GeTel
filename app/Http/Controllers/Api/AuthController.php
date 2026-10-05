<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    public function register(RegisterRequest $request)
    {
        try {

            $user = $this->authService->register(
                $request->validated(),
                $request->file('profile_photo')
            );

            return response()->json([
                'message' => 'Inscription réussie. Vérifiez votre e-mail.',
                'email' => $user->email,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Erreur lors de l\'inscription.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Vérification OTP après inscription
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        try {

            $user = $this->authService->verifyRegistrationOtp(
                $request->email,
                $request->otp
            );

            return response()->json([
                'message' => 'OTP vérifié avec succès.',
                'user' => $user,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Erreur lors de la vérification de l\'OTP.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }


    /**
     * Connexion
     */
    public function login(LoginRequest $request)
    {
        try {

            $result = $this->authService->login(
                $request->email,
                $request->password
            );

            return response()->json([
                'message' => 'Connexion réussie.',
                'token' => $result['token'],
                'user' => $result['user'],
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Erreur lors de la connexion.',
                'error' => $e->getMessage(),
            ], 401);
        }
    }


    /**
     * Profil de l'utilisateur connecté
     */
    public function profile(Request $request)
    {
       try{
            return response()->json([
                'user' => $request->user(),
            ]);
       } catch(\Exception $e){
            return response()->json([
                'Message'
            ]);
       }
    }


    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ]);
    }


    /**
     * Demande de réinitialisation du mot de passe
     */
    public function forgotPassword(
        ForgotPasswordRequest $request
    ) {
        $this->authService->sendPasswordResetOtp(
            $request->email
        );

        return response()->json([
            'message' =>
                'Si cette adresse existe, un code a été envoyé.',
        ]);
    }


    /**
     * Réinitialisation du mot de passe
     */
    public function resetPassword(
        ResetPasswordRequest $request
    ) {
        try {

            $this->authService->resetPassword(
                $request->email,
                $request->otp,
                $request->password
            );

            return response()->json([
                'message' =>
                    'Mot de passe réinitialisé avec succès.',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }


    /**
     * Modification du profil
     */
    public function updateProfile(
        UpdateProfileRequest $request
    ) {
        $user = $request->user();

        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {

            // Supprimer l'ancienne photo
            if ($user->profile_photo) {

                Storage::disk('public')
                    ->delete($user->profile_photo);
            }

            // Enregistrer la nouvelle photo
            $data['profile_photo'] =
                $request->file('profile_photo')
                    ->store(
                        'profile_photos',
                        'public'
                    );
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profil mis à jour.',
            'user' => $user->fresh(),
        ]);
    }
}
