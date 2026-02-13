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

    /**
     * Upload profile image
     */
    public function uploadProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Delete old image if exists
        if ($user->profile_image && \Storage::disk('public')->exists('profile_images/' . $user->profile_image)) {
            \Storage::disk('public')->delete('profile_images/' . $user->profile_image);
        }

        // Store new image
        $imageName = time() . '_' . $user->id . '.' . $request->file('profile_image')->getClientOriginalExtension();
        $request->file('profile_image')->storeAs('profile_images', $imageName, 'public');

        $user->profile_image = $imageName;
        $user->save();

        return back()->with('success', 'Profile image updated successfully');
    }

    /**
     * Remove profile image
     */
    public function removeProfileImage()
    {
        $user = auth()->user();

        if ($user->profile_image && \Storage::disk('public')->exists('profile_images/' . $user->profile_image)) {
            \Storage::disk('public')->delete('profile_images/' . $user->profile_image);
        }

        $user->profile_image = null;
        $user->save();

        return back()->with('success', 'Profile image removed successfully');
    }
}



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

    /**
     * Upload profile image
     */
    public function uploadProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Delete old image if exists
        if ($user->profile_image && \Storage::disk('public')->exists('profile_images/' . $user->profile_image)) {
            \Storage::disk('public')->delete('profile_images/' . $user->profile_image);
        }

        // Store new image
        $imageName = time() . '_' . $user->id . '.' . $request->file('profile_image')->getClientOriginalExtension();
        $request->file('profile_image')->storeAs('profile_images', $imageName, 'public');

        $user->profile_image = $imageName;
        $user->save();

        return back()->with('success', 'Profile image updated successfully');
    }

    /**
     * Remove profile image
     */
    public function removeProfileImage()
    {
        $user = auth()->user();

        if ($user->profile_image && \Storage::disk('public')->exists('profile_images/' . $user->profile_image)) {
            \Storage::disk('public')->delete('profile_images/' . $user->profile_image);
        }

        $user->profile_image = null;
        $user->save();

        return back()->with('success', 'Profile image removed successfully');
    }
}


