<?php 

namespace App\Services;

use App\Models\User;
use App\Models\Otp;
use App\Models\Notification\SystemNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
//use Illuminate\Http\Request;

use Exception;

class AuthService
{

    public function register(array $data, ?UploadedFile $photo = null): User
    {
        $photoPath = null;
        if($photo) {
            $photoPath = $photo->store('profile_photos', 'public');
        }
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->profile_photo = $photoPath;
        $user->save();
      

        
       /* $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'profile_photo' => $photoPath,
        ]);
        */

        $this->createOtp($user, 'register');
        return $user;
    }
    
    private function createOtp(User $user, string $type): void
    {
       // $otp = new Otp();
      //  $otp->user_id = $user->id;
      //  $otp->type = $type;
        $otp = rand(100000, 999999); // Generate a random 6-digit code
        Otp::where('email', $user->email)
        ->where('type', $type)
        ->delete();

        Otp::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp' => Hash::make((string) $otp),
            'type' => $type,
            'expires_at' => now()->addMinutes(10),
        ]);

       $user->notify(
            new SystemNotification(
                "Your OTP code is: " . (string) $otp)); // Send the OTP to the user via notification
      
       
                // $otp->save();

        // Optionally, you can send the OTP to the user via email or SMS here

       // return $otp;
    }

    public function login(string  $email, string $mdp):array 
    {
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($mdp, $user->password)) {
            throw new \Exception('Informations de connexion invalides');
        }
        if(!$user->email_verified_at) {
            throw new \Exception('Veuillez vérifier votre adresse e-mail avant de vous connecter');
        }   
        $token = $user->createToken('auth_token_AI_IMG')->plainTextToken;
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function verifyRegistrationOtp(string $email, string $otp): User 
    {
        $record = Otp::where('email', $email)
            ->where('type', 'register')
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$record) {
            throw new \Exception('Code OTP invalide');
        }

        if ($record->expires_at-> isPast()) {
            throw new \Exception( 'Le code OTP a expiré.');
        }
        if($record->attempts >= 5) {
            throw new \Exception(
                'Nombre maximum de tentatives atteint. Veuillez demander un nouveau code OTP.'
            );
        }

        $record->increment('attempts', 1);
        if (!Hash::check($otp, $record->otp)) {
            throw new \Exception('Code OTP invalide');
        }

        $user = User::where('email', $email)->first();
        $user->update(['email_verified_at' => now()]);
        $record->update(['verified_at' => now()]);

        return $user;
    }

    public function sendPasswordResetOtp(string $email): void
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new \Exception('Utilisateur non trouvé');
        }

        $this->createOtp($user, 'password_reset');
    }

    public function resetPassword(string $email, string $otp, string $newPassword): void
    {
        $record = Otp::where('email', $email)
            ->where('type', 'password_reset')
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$record) {
            throw new \Exception('Code OTP invalide');
        }

        if ($record->expires_at->isPast()) {
            throw new \Exception('Le code OTP a expiré.');
        }
        if($record->attempts >= 5) {
            throw new \Exception(
                'Nombre maximum de tentatives atteint. Veuillez demander un nouveau code OTP.'
            );
        }

        $record->increment('attempts');
        if (!Hash::check($otp, $record->otp)) {
            throw new \Exception('Code OTP invalide');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
             throw new \Exception(
                 'Utilisateur introuvable.'
             );
        }
        $user->update(['password' => Hash::make($newPassword)]);
        $record->update(['verified_at' => now()]);

            // Invalider les anciens tokens
         $user->tokens()->delete();
    }


}