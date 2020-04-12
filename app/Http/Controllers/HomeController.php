<?php

namespace App\Http\Controllers;
use App\Robo;
use App\Processo;

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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $processo = Processo::count();
        $robos = Robo::count();
        $robosOn = Robo::where('status', 1)->count();
        $robosOff = Robo::where('status', 0)->orWhere('status', 2)->count();
        $robosErro = Robo::where('status', 2)->count();
        return view('dashboard',compact('processo','robos','robosOff','robosOn','robosErro'));
    }


}
