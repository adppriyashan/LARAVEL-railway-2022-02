<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Train;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $traincount = Train::where('status', 1)->count();
        $bookingcountQuery = Booking::where('status', 1);

        if (Auth::user()->usertype == 2) {
            $bookingcountQuery->where('user', Auth::user()->id);
        }

        $bookingcount = $bookingcountQuery->count();
        $availablebookings=$bookingcountQuery->get();

        $traincount = Train::where('status', 1)->count();
        return view('home', compact(['traincount', 'bookingcount', 'availablebookings']));
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }
}
