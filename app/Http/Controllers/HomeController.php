<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {

            $role = Auth::user()->getRoleNames();

            switch ($role[0]) {
                case 'superadmin':
                    return view('dashboard/admin_dashboard');
                    break;
                case 'admin':
                    return view('dashboard/admin_dashboard');
                    break;
                case 'contact':
                    return view('dashboard/seller_dashboard');
                    break;
                default:
                    return view('home');
                    break;
            }
        }
    }
}
