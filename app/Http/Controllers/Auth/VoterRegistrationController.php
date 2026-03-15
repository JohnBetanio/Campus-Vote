<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Inertia\Inertia;

class VoterRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return Inertia::render('Auth/VoterRegister');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:voters',
            'password' => 'required|string|min:8|confirmed',
            'course' => 'required|string|max:255',
        ]);

        $voter = Voter::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'course' => $request->course,
        ]);

        event(new Registered($voter));

        auth()->guard('voter')->login($voter);

        return redirect()->route('voter.dashboard');
    }
}