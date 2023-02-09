<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index(): View
    {
        return view('welcome');
    }
    public function dashboard(): View
    {

        $users = DB::table('users')->count();
        
        return view('dashboard', compact('users'));
    }
}
