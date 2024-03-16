<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  	public function index()
    {
        if (session()->has('urlDashboard')) {
            $url = session()->get('urlPrevious') != null ? session()->get('urlPrevious') : session()->get('urlDashboard');
            session()->forget('urlDashboard');
            session()->forget('urlPrevious');
            return redirect($url);
        } else {
            $data['title'] = 'I-Dashboard | Welcome';
            return view('index')->with($data);
        }
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            session(['urlDashboard' => route('dashboard')]);
            return view('auth.login');
        }
    }    
}


