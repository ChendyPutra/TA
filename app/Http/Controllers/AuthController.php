<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
     // Fungsi untuk menangani redirect ke Google OAuth
     public function redirectToGoogle()
     {
         return Socialite::driver('google')->redirect();
     }
 
     // Fungsi untuk menangani callback setelah Google memberikan izin
     public function handleProviderCallback()
     {
         // Mendapatkan data pengguna dari Google
         $googleUser = Socialite::driver('google')->user();
 
         // Cek apakah pengguna sudah terdaftar berdasarkan email
         $user = User::where('email', $googleUser->getEmail())->first();
 
         if (!$user) {
             // Jika pengguna belum terdaftar, buat pengguna baru dan tetapkan password default
             $user = User::create([
                 'name' => $googleUser->getName(),
                 'email' => $googleUser->getEmail(),
                 'password' => Hash::make('defaultpassword') // Password default
             ]);
         }
 
         // Login pengguna
         Auth::login($user);
 
         // Arahkan pengguna setelah login
         return redirect()->route('home'); // Ganti dengan rute yang sesuai
     }
     
}
