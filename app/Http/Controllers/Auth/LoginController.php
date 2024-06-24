<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        try{
        $user = Socialite::driver('google')->user();

        // Cek jika user sudah terdaftar
        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            Auth::login($existingUser);
        } else {
            // Buat user baru jika belum terdaftar
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
            ]);
            Auth::login($newUser);
        }

            return redirect()->to('/dashboard'); // Redirect ke dashboard setelah login
        }catch(Exception $e) {
            return redirect()->to('/login')->with('error', 'Failed to login with Google. Please try again.');
        }
    }
}
