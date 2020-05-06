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
    public function index(Robo $robo)
    {
        $processo = Processo::count();
        $robos = Robo::count();
        $robosOn = Robo::where('status', 1)->count();
        $totalMesAnterior = $this->returnQuantidadeProcesso();    
        $robosOff = Robo::where('status', 0)->orWhere('status', 2)->count();
        $robosErro = Robo::where('status', 2)->count();
        if($processo > 0 && $totalMesAnterior >0){
            $mesPassado = number_format(($processo / $totalMesAnterior) * 100,2,'.','.');
        }else{
            $mesPassado = "0.00";
        }
        return view('dashboard',compact('processo','mesPassado','totalMesAnterior','robos','robosOff','robosOn','robosErro'));
    }

    public function returnQuantidadeProcesso(){
        $mesAnterior = \Carbon\Carbon::now()->subMonth()->toDateString();
        $mesAtual = \Carbon\Carbon::now()->toDateString();
        $processos = Processo::whereBetween('created_at', [$mesAnterior, $mesAtual])->get()->count();
        return $processos;
    }
}
