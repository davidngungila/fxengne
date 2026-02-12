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
}


