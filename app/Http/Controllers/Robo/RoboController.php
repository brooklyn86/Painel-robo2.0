<?php

namespace App\Http\Controllers\Robo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Robo;
use App\Processo;
class RoboController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $processo = Processo::count();

        $robos = Robo::count();
        $robosOn = Robo::where('status', 1)->count();
        $robosOff = Robo::where('status', 0)->orWhere('status', 2)->count();
        $robosErro = Robo::where('status', 2)->count();
        return view('bot.create',compact('processo','robos','robosOff','robosOn','robosErro'));
    }

    public function index(Request $request)
    {
        $robo_id = $request->id;
        $processo = Processo::count();

        $robos = Robo::count();
        $robosOn = Robo::where('status', 1)->count();
        $robosOff = Robo::where('status', 0)->orWhere('status', 2)->count();
        $robosErro = Robo::where('status', 2)->count();
        return view('bot.create',compact('processo','robo_id','robos','robosOff','robosOn','robosErro'));
    }
    public function returnRoboDatatable()
    {
        return Datatables::of(Robo::all())
        ->addColumn('statusForm', function($data){
 
            if($data->status == 0){
                  $icon = '<a class="nav-link" href="#">
                <i class="ni ni-button-power text-danger"></i> 
            </a>';
            }
            if($data->status == 1){
                $icon = '<a class="nav-link" href="#">
                <i class="ni ni-sound-wave text-success"></i> 
            </a>';
            }
            if($data->status == 2){
                $icon = '<a class="nav-link" href="#">
                <i class="fas fa-exclamation-triangle text-warning"></i>
            </a>';
            }
 
            return $icon;
             
         })
         ->addColumn('processos', function($data){
            
            return Processo::where('robo_id', $data->id)->count();
             
         })
         ->addColumn('actions', function($data){
 
  
            $icon = '<a class="btn btn-info" href="/processos/view/robo/'.$data->id.'/processos">
                Ver Processos
            </a>';
            

            return $icon;
             
         })
         ->rawColumns(['statusForm','actions'])->make();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $robo = new Robo;
       $robo->name = $request->name;
       $robo->description = $request->description;
       $response = $robo->save();
       if($response){
           return Redirect()->back()->with('success', 'Robo Cadastrado com Sucesso');
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
