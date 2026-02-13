<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index()
    {
        return view('journal.index');
    }

    public function entries()
    {
        return view('journal.entries');
    }

    public function addNote()
    {
        return view('journal.add-note');
    }

    public function mistakes()
    {
        return view('journal.mistakes');
    }

    public function weeklyReview()
    {
        return view('journal.weekly-review');
    }
}





