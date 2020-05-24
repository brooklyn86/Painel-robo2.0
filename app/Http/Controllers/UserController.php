<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\Robo;
use App\Processo;
use App\CampoProcesso;
use App\ProcessoSituacao;
use App\AcordoProcesso;

use Yajra\DataTables\Facades\DataTables;
class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        $processo = Processo::count();
        $robos = Robo::count();
        $robosOn = Robo::where('status', 1)->count();
        $robosOff = Robo::where('status', 0)->orWhere('status', 2)->count();
        $robosErro = Robo::where('status', 2)->count();
       return view('users.index',compact('processo','robos','robosOff','robosOn','robosErro'));
    
    }
}
