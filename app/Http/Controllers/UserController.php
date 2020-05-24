<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
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
    public function register(Request $request){
        if(auth()->user()->role_id == 1){
            $user = new User;
            $user->name = $request->nome;
            $user->email = $request->email;
            $user->password = Hash::make($request->senha);
            $user->role_id = $request->permissao;
            $response = $user->save();

            if($response){
                return redirect()->back()->with('success', 'Usuario Cadastrado');
            }
            return redirect()->back()->with('error', 'Erro ao Cadastrar');
        }
        return redirect()->back();

    }
    public function update(Request $request){
        if(auth()->user()->role_id == 1){
            $user = User::find($request->usuario_id);
            $user->name = $request->nome;
            $user->email = $request->email;
            if($request->senha != ''){
                $user->password = Hash::make($request->senha);
            }
            $user->role_id = $request->permissao;
            $response = $user->save();

            if($response){
                return redirect()->back()->with('success', 'Usuario Atualizado');
            }
            return redirect()->back()->with('error', 'Erro ao Atualizar');
        }
        return redirect()->back();

    }
    public function delete(Request $request){
        if(auth()->user()->role_id == 1){
            $user = User::find($request->id)->delete();

            if($user){
                return redirect()->back()->with('success', 'Usuario Deletado');
            }
            return redirect()->back()->with('error', 'Erro ao Deletar');
        }
        return redirect()->back();

    }

    public function getUser(Request $request){
        if(auth()->user()->role_id == 1){
            $usuario = User::find($request->id);

           return view('users.view',compact('usuario'));
        }else{
            return redirect()->back();
        }
    }
    public function returnUserPlataforma(){

        return Datatables::of(User::all())
        ->addColumn('permissao', function($data){
           if($data->role_id == 0) {
            return '<spam class="btn btn-sm btn-success">Usuario</spam>';
               
           }else{
            return '<spam class="btn btn-sm btn-danger">Administrador</spam>';

           }
            })
            ->addColumn('actions', function($data){
            $icon = '<button class="btn btn-dark btn-sm" data-processo="'.$data->id.'" data-toggle="modal" data-target="#editarUsuario">
                Editar
            </button>
            <a href="/user/'.$data->id.'/excluir"><button class="btn btn-danger btn-sm">
                Excluir
            </button></a>';
                
                return $icon;
                
            })
            ->rawColumns(['actions','permissao'])->make();
    
    }
}
