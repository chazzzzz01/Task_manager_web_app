<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskPageController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'tasks' => Auth::user()->tasks()->latest()->get(),
        ]);
    }
}
