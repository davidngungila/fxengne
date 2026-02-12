<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function settings()
    {
        return view('profile.settings');
    }

    public function security()
    {
        return view('profile.security');
    }

    public function notifications()
    {
        return view('profile.notifications');
    }

    public function logs()
    {
        return view('profile.logs');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = \Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully');
    }
}


