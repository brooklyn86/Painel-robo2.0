<?php

namespace App\Http\Controllers\Processo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Robo;
use App\Processo;
use App\CampoProcesso;
use App\ProcessoSituacao;
use App\AcordoProcesso;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Requests as api;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
class ProcessoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $robo_id = $request->id;
        $robo = Robo::find($robo_id);
        $processo = Processo::count();
        $robos = Robo::count();
        $robosOn = Robo::where('status', 1)->count();
        $robosOff = Robo::where('status', 0)->orWhere('status', 2)->count();
        $robosErro = Robo::where('status', 2)->count();

        return view('processo.index',compact('processo','robo','robo_id','robos','robosOff','robosOn','robosErro'));
    }

    public function returnQuantidadeProcesso(){
        $mesAnterior = \Carbon\Carbon::now()->subMonth()->toDateString();
        $mesAtual = \Carbon\Carbon::now()->toDateString();
        $processos = Processo::whereBetween('created_at', [$mesAnterior, $mesAtual])->get()->count();
        return $processos;
    }
    public function upload(Request $request){
            // Define o valor default para a variável que contém o nome da imagem 
        $nameFile = null;

        // Verifica se informou o arquivo e se é válido
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            
            // Define um aleatório para o arquivo baseado no timestamps atual
            // $name = uniqid(date('HisYmd'));
    
            // Recupera a extensão do arquivo
            // $extension = $request->file->extension();
            $nameFile = $request->file->getClientOriginalName();
    
            // // Define finalmente o nome
            // $nameFile = "{$name}.{$extension}";
    
            // Faz o upload:
            $upload = $request->file->storeAs('/public/pdfs/',$nameFile);
            // Se tiver funcionado o arquivo foi armazenado em storage/app/public/categories/nomedinamicoarquivo.extensao
    
            // Verifica se NÃO deu certo o upload (Redireciona de volta)
            if ( !$upload )
                return Response()->json(['error' => true, 'message' => 'Falha ao fazer upload'],500);
            return Response()->json(['error' => false, 'message' => 'Upload com sucesso']);
    
        }
    }
    public function getProcesso(Request $request){
       return Datatables::of(Processo::where('robo_id',$request->robo_id))
       ->addColumn('campos', function($data){
          $robo = CampoProcesso::where('processo_id',$data->id)->get();
           
           return $robo->toArray();
            
        })
       ->make();
       return Response()->json($processo);
    }
    public function submitProcessoDomain(Request $request, CampoProcesso $campoProcesso){
        $processos = $request->data;
        $processos = json_decode($processos);
        $process = [];
        foreach($processos as $processo){
           $campos = $campoProcesso->where('processo_id', $processo->id)->get()->toArray();
           array_push($process, array('campos' => $campos, 'processo' => ['id' => $processo->id,'number' => $processo->processo, 'robo' => $processo->robo_id]));
        }
        $response = api::post('http://localhost', $process);
        if($response->status_code == 200){
            return Response()->json('success', 'Processos Enviados com Sucessos');
        }
        return Response()->json('error', 'Falha ao Enviar');

    }
    public function returnProcessDatatable(Request $request)
    {
        return Datatables::of(Processo::where('robo_id',$request->id))
        ->addColumn('robo', function($data){
           $robo = Robo::find($data->robo_id);
            
            return $robo->name;
             
         })
         ->addColumn('actions', function($data){
            $icon = '<button class="btn btn-dark btn-sm" data-processo="'.$data->id.'" data-toggle="modal" data-target="#processoViewModal">
                Ver Processo
            </button>';
             
             return $icon;
              
          })
          ->rawColumns(['actions'])->make();

    }
    public function situacaoProcessual(Request $request){
        if(auth()->user()->role_id == 1){
            $processo = Processo::count();
            $robos = Robo::count();
            $robosOn = Robo::where('status', 1)->count();
            $robosOff = Robo::where('status', 0)->orWhere('status', 2)->count();
            $robosErro = Robo::where('status', 2)->count();
            $logins = ProcessoSituacao::where('login',$request->login)->select('login')->distinct()->groupBy('login')->get();
            
            $login = $request->login;
           return view('situacao.index',compact('processo','robos','robosOff','robosOn','robosErro','logins', 'login'));
        }else{
           return redirect()->back();
        }
        
    }
    public function acordoProcessual(Request $request){
        if(auth()->user()->role_id == 1){
          $processo = ProcessoSituacao::find($request->id);
          if($processo->status != 0){
              $processo->status=0;
              $processo->save();
          }
           $acordos = AcordoProcesso::where('processo_id',$request->id)->get();
           return view('situacao.view',compact('acordos','processo'));
        }else{
           return redirect()->back();
        }
        
    }
    public function returnProcessSituacao(Request $request)
    {
        return Datatables::of(ProcessoSituacao::where('login',$request->login)->get())
        ->addColumn('precatoriaStatus', function($data){
            if($data->status == 1){
             return $data->precatoria." <spam class='btn btn-sm btn-success'>Novo</spam>";

            }
            if($data->status == 2){
                return $data->precatoria." <spam class='btn btn-sm btn-warning'>Atualizado</spam>";
   
            }
            if($data->status == 3){
                return $data->precatoria." <spam class='btn btn-sm btn-warning'>Atualizado</spam>";
   
            }
            if($data->status == 0){
                return $data->precatoria;
            }
              
          })
         ->addColumn('actions', function($data){
            $icon = '<button class="btn btn-dark btn-sm" data-processo="'.$data->id.'" data-toggle="modal" data-target="#processoAcordoViewModal">
                Ver Acordos
            </button>';
             
             return $icon;
              
          })
          ->rawColumns(['actions','precatoriaStatus'])->make();

    }
    public function situacaoProcesso(Request $request){
       
        $situacao = 'Aguardando Upload';
        if($request->situacao != ''){
            $situacao = $request->situacao;
        }
        
        $verificaProcesso = ProcessoSituacao::where('precatoria', $request->precatoria)->first();
        if($verificaProcesso){
            if($request->dataSolicitacao != ''){
                $dataAcordo = explode('/',$request->dataSolicitacao);
                $data = \Carbon\Carbon::create($dataAcordo[2],$dataAcordo[1],$dataAcordo[0],0,0,0);
                    if($data->toDate() > $verificaProcesso->data){
                        if($verificaProcesso->situacao != $situacao){
                            $verificaProcesso->situacao = $situacao;
                            $verificaProcesso->status = 2;
                            $verificaProcesso->url = $request->url;
                            $verificaProcesso->data = $data;
                            $verificaProcesso->save();
                            $dados =  [
                                'processo' => $verificaProcesso,
                                'messagem' => "Precatoria atualizada na plataforma:",
                                'acordo' => []
                            ];
                            try{
                                Mail::send(new \App\Mail\SendMailNotificaAcordo($dados));
                            }catch(Exception $e){
                                echo json_encode('enviado'. $e);
                                
                            }
                        }
                    }
                }
            if($request->protocolo != ""){
                
                $acordoValida = AcordoProcesso::where('protocolo',$request->protocolo)->first();
                if(!$acordoValida){
                    $dataAcordo = explode('/',$request->dataSolicitacao);
                    $data = null;
                    if(isset($dataAcordo[2])){
                        $data = \Carbon\Carbon::create($dataAcordo[2],$dataAcordo[1],$dataAcordo[0],0,0,0)->toDate();
        
                    }
                    $verificaProcesso = ProcessoSituacao::where('precatoria', $request->precatoria)->first();
                    if($data > $verificaProcesso->data){
                        $verificaProcesso->status = 3;
                        $verificaProcesso->data = $data;
                        $verificaProcesso->situacao = $situacao;
                        $verificaProcesso->url = $request->url;
                        $verificaProcesso->save();
                        $dados =  [
                            'processo' => $verificaProcesso,
                            'messagem' => "Precatoria atualizada na plataforma:",
                            'acordo' => []
                        ];
                        try{
                            Mail::send(new \App\Mail\SendMailNotificaAcordo($dados));
                        }catch(Exception $e){
                            echo json_encode('enviado'.$e);
                            
                        }
                    }
                    $verificaAcordo = AcordoProcesso::where('protocolo', $request->protocolo)->first();

                    if(!$verificaAcordo){
                        $ordemProcesso = new AcordoProcesso;
                        $ordemProcesso->processo_id = $verificaProcesso->id;
                        $ordemProcesso->protocolo = $request->protocolo;
                        $ordemProcesso->texto = $request->texto;
                        $ordemProcesso->situacao = $situacao;
                        $ordemProcesso->save();
                        $dados =  [
                            'processo' => $verificaProcesso,
                            'messagem' => "Novo Acordo na plataforma plataforma:",
                            'acordo' => []
                        ];
                        try{
                            Mail::send(new \App\Mail\SendMailNotificaAcordo($dados));
                        }catch(Exception $e){
                            echo json_encode('enviado'.$e);
                        }
                    }
                    
                    return Response()->Json(['processo' => $verificaProcesso, 'ordem_processo', $ordemProcesso]);

                }
            }

        }else{
           
            $situacao = $request->situacao;
            
            if($request->situacao != ""){
                $situacao = $request->situacao;


            }else{
                $situacao = "Aguardando Upload";

            }
            
            $dataAcordo = explode('/',$request->dataSolicitacao);

            $data = null;
            if(isset($dataAcordo[2])){
                $data = \Carbon\Carbon::create($dataAcordo[2],$dataAcordo[1],$dataAcordo[0],0,0,0);

            }
            $processo = new ProcessoSituacao;
            $processo->precatoria = $request->precatoria;
            $processo->situacao = $situacao;
            $processo->data = $data;
            $processo->url = $request->url;
            $processo->login = $request->login;
            $processo->save();
            $dados =  [
                'processo' => $verificaProcesso,
                'messagem' => "Nova Precatoria na plataforma:",
                'acordo' => []
            ];
            try{
                Mail::send(new \App\Mail\SendMailNotificaAcordo($dados));
            }catch(Exception $e){
                echo json_encode('enviado'.$e);
                
            }
            $ordemProcesso = [];
            if($request->protocolo != ""){
                $dataAcordo = explode('/',$request->dataSolicitacao);
                $ordemProcesso = new AcordoProcesso;
                $ordemProcesso->processo_id = $processo->id;
                $ordemProcesso->protocolo = $request->protocolo;
                $ordemProcesso->texto = $request->texto;
                $ordemProcesso->situacao = $request->situacao;
            
                $ordemProcesso->save();

            }

            return Response()->Json(['processo' => $processo, 'ordem_processo', $ordemProcesso]);

        }
        return Response()->Json(['processo' => [], 'ordem_processo',[]]);

    }
    public function extractPdfToBot(Request $request){
        
        $processFind = Processo::where('processo',$request->precatoria)->first();
        if(!$processFind){
            $processo = new Processo();
            $processo->processo = $request->precatoria;
            $processo->robo_id = $request->robo_id;
            $processo->save();
            $i = 0;
            $req = $request->all();
            foreach($req as $key => $value){
                if($key != "robo_id"){
                    if($key == "valor"){
                        $value = number_format($value,2,',','.');
                    }else{
                        $value = $value;
                    }
                    if(strlen($value) > 75){
                        $type = 'textarea';
                    }else{
                        $type = "text";
                    }
                    $key = preg_replace("/[_]+/", " ", $key);
                    $campo = new CampoProcesso;
                    $campo->processo_id = $processo->id;
                    $campo->key = $type;
                    $campo->name = ucwords($key);
                    $campo->value = $value;
                    $campo->order = $i;
                    $campo->save();
    
                    $i++;
                }
               
            }
        }
        
        return Response()->json("sucesso");
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $parser = new \Smalot\PdfParser\Parser();
        $file = base_path('storage/app/public/pdfs/'.$request->processo.'.pdf');
        $pdf = $parser->parseFile($file);
        $processo = new Processo;
        $nome = 0;
        $cpf = 0;
        $valor_principal = 0;
        $valor_juro = 0;
        $valor_req = 0;
        $processoV = 0;
        $contratuais = 0;
        $nomeReu = 0;
        $criado = 0;
        $data_conta = 0;
        $assunto_cjf = 0;
        $processoAnterior = [];
        $linhas = explode("\n", $pdf->getText());
        $nome_reu = "";
        $segundo_cpf = "";
        $processo->processo = $request->processo;
        $processo->robo_id = $request->robo_id;
        $processo->save();
        $totalLinhas = count($linhas);
        foreach($linhas as $linha){
            
            if(preg_match('/Data de Nascimento/', $linha) ){
                $explode = explode(' ', $linha);
                $campo = new CampoProcesso;
                $campo->processo_id = $processo->id;
                $campo->key = 'data';
                $campo->name = 'Data de Nascimento';
                $campo->order = 3;
                $data = (isset($explode[3]) ? trim($explode[3])  : "");
           
                $data = explode('/',$data);
                $data = \Carbon\Carbon::createFromDate($data[2].'-'.$data[1].'-'.$data[0])->toDateString();

                $campo->value = $data;
                $campo->save();
            }
            if(preg_match('/Processo Anterior nº/', $linha) ){
                array_push($processoAnterior, $linha);
            }
            $explode = explode(':', $linha);
            if($processoV == 0){
                if($explode[0] == "Processo"){
                    $processoV =1;
                    $numero = explode(" ",$explode[1]);
                    $processo->processo = (isset($numero[2]) ? trim($numero[2]) : trim($numero[1] ) );
                    
                }
            }
            // if($explode[0] == "Nome da Vara"){
            //     array_push($processoAnterior, $explode[1]);

            // }
            // if($explode[0] == "Data Protocolo"){
            //     array_push($processoAnterior, $explode[1]);

            // }
            if($assunto_cjf == 0){
                if($explode[0] == "Assunto CJF"){
                    $campo = new CampoProcesso;
                    $campo->processo_id = $processo->id;
                    $campo->key = 'text';
                    $campo->name = 'Assunto CJF';
                    $campo->value =  trim($explode[1]);
                    $campo->order = 3;

                    $campo->save();
                    $assunto_cjf++;
                }
            }
            if($data_conta == 0){
                if($explode[0] == "Data da Conta"){
                    $campo = new CampoProcesso;
                    $campo->processo_id = $processo->id;
                    $campo->key = 'data';
                    $campo->name = 'Data da Conta';
                    $campo->order = 4;

                    $data = (isset($explode[1]) ? trim($explode[1])  : "");
                
                    $data = explode('/',$data);
                    $data = \Carbon\Carbon::createFromDate($data[2].'-'.$data[1].'-'.$data[0])->toDateString();
                    $campo->value = $data;
                    $campo->save();
                    $data_conta++;
                }
            }
            if($contratuais == 0) {
                if(preg_match('/Contratuais/', $linha) ){
                    $campo = new CampoProcesso;
                    $campo->processo_id = $processo->id;
                    $campo->key = 'radiobutton-disable';
                    $campo->name = 'Contratuais-sim,não';
                    $campo->value =  'sim';
                    $campo->save();
                    $contratuais = 1;
                }
            }

            if($nome == 0){
                if($explode[0] == "Nome"){
                    $campo = new CampoProcesso;
                    $campo->processo_id = $processo->id;
                    $campo->key = 'text';
                    $campo->name = 'Nome';
                    $campo->value = (isset($explode[1]) ? trim($explode[1])  : "");
                    $campo->order = 0;
                    $campo->save();
                }
            }
            if($explode[0] == "Réu\t"){
                $nome = 1;
            }
            if($cpf == 0){ 
                if(preg_match('/CPF/', $linha)){

                    $campo = new CampoProcesso;
                    $campo->processo_id = $processo->id;
                    $campo->key = 'text';
                    $campo->name = 'CPF';
                    $campo->value = (isset($explode[1]) ? trim($explode[1])  : "");
                    $campo->order = 1;

                    $campo->save();
                    $cpf=1;
                }
            }
            // if($cpf == 1){ 
            //     if(preg_match('/CPF/', $linha)){
            //         if($processo->cpf != trim($explode[1])){
            //             $campo = new CampoProcesso;
            //             $campo->processo_id = $processo->id;
            //             $campo->key = 'text';
            //             $campo->name = 'Segundo CPF';
            //             $campo->value = (isset($explode[1]) ? trim($explode[1])  : "");
            //             $campo->order = ;

            //             $campo->save();
            //             $cpf=2;
            //         }
            //     }
            // }
            if($nome > 0 && $nomeReu != 1){
                if($explode[0] == "Nome"){
                    $campo = new CampoProcesso;
                    $campo->processo_id = $processo->id;
                    $campo->key = 'text';
                    $campo->name = 'Nome do Reu';
                    $campo->value = (isset($explode[1]) ? trim($explode[1])  : "");
                    $campo->order = 2;

                    $campo->save();
				  $nome++;
				  $nomeReu = 1;
                }
            }
  
            if($valor_principal < 2){
                if($explode[0] == "Valor Principal"){
                    if($valor_principal == 1){
                        $campo = new CampoProcesso;
                        $campo->processo_id = $processo->id;
                        $campo->key = 'text';
                        $campo->name = 'Valor Principal';
                        $campo->value = (isset($explode[1]) ? trim($explode[1])  : "");
                        $campo->order = 5;

                        $campo->save();
                    }
                    $valor_principal++;                    
                }
            }
       
            if($valor_juro < 2){
                if($explode[0] == "Valor Juros"){
                    if($valor_juro == 1){
                        $campo = new CampoProcesso;
                        $campo->processo_id = $processo->id;
                        $campo->key = 'text';
                        $campo->name = 'Valor Juros';
                        $campo->value = (isset($explode[1]) ? trim($explode[1])  : "");
                        $campo->order = 6;

                        $campo->save();
                        
                    }
                    $valor_juro++;

                }
            }
            if($valor_req == 0){
                if($explode[0] == "Valor Total do Requerente"){
                    $campo = new CampoProcesso;
                    $campo->processo_id = $processo->id;
                    $campo->key = 'text';
                    $campo->name = 'Valor Total do Requerente';
                    $campo->value = (isset($explode[1]) ? trim($explode[1])  : "");
                    $campo->order = 7;

                    $campo->save();
                    $valor_req = 1;
                }
            }
            if($explode[0] == "Número de Meses (Exerc. Anteriores)"){
                $valor_req = 1;
                $meses = explode('D', $explode[1]);
                $campo = new CampoProcesso;
                $campo->processo_id = $processo->id;
                $campo->key = 'text';
                $campo->name = 'Número de Meses (Exerc. Anteriores)';
                $campo->value = (isset($meses[0]) ? trim($meses[0])  : "");
                $campo->order = 9;

                $campo->save();
                
            }
            if($criado == 0){
                if(preg_match('/Criado em/', $linha)){
                    $nlinha = explode(' ', $linha);
                    $campo = new CampoProcesso;
                    $campo->processo_id = $processo->id;
                    $campo->key = 'info';
                    $campo->name = 'Criado em';
                    $campo->value = 'Criado em:' .$nlinha[5].' '.$nlinha[6];
                    $campo->order = 8;

                    $campo->save();
                    $criado++;
                }
            }
        }
        // $processo->processos_anteriores = json_encode($processoAnterior);
        // $processo->nome_reu = $nome_reu;
        // $processo->segundo_cpf = $segundo_cpf;
        // $processo->robo_id = 1;
      
        // $processo->save();

        return Response()->json($processo);
    }

    public function roboPJe(Request $request){

    $url =  "https://esaj.tjsp.jus.br/pastadigital/getPDF.do?".base64_decode($request->url);
    $processo = Processo::where('id',$request->id)->first();
    
    if(isset($processo)){
        $filename =  $request->code.'.pdf';
        $tempImage = base_path('public/storage/pdf/'.$filename);
        $arquivo = \File::copy($url, $tempImage);
        $size = filesize(base_path('public/storage/pdf/'.$filename));
        if($size < 20000){
            $parser = new \Smalot\PdfParser\Parser();
            $pdf  = $parser->parseFile('http://localhost:8000/storage/pdf/'.$filename);
            $processos = null;
            $conteudo  = $pdf->getPages();

            foreach ($conteudo as $key) {
                $pagina = $key->getText();

                $linha = explode("\n", $pagina);

                foreach ($linha as $l) {
                    $dado = explode(':', $l);
                    if($dado[0] == "CPF"){
                        $processos = Order::find($request->id);
                        $cpf = preg_split('/\s+/',$dado[1]);
                        $processos->cpf = $cpf[1];
                        $campo = new CampoProcesso;
                        $campo->processo_id = $processo->id;
                        $campo->key = 'text';
                        $campo->name = 'CPF';
                        $campo->value = (isset($cpf[1]) ? trim($cpf[1])  : "");
                        $campo->order = 0;
                        $campo->save();
                    }
                    if($dado[0] == "Requerente "){
                        $reqteRE = trim($dado[1]);
                        $reqteRA = trim($reqteRE, "*");
                        $reqte = trim($reqteRA);
                        $campo = new CampoProcesso;
                        $campo->processo_id = $processo->id;
                        $campo->key = 'text';
                        $campo->name = 'Requerente';
                        $campo->value = (isset($reqte) ? trim($reqte)  : "");
                        $campo->order = 1;
                        $campo->save();
                    }
                    if($dado[0] == "Entidade devedora"){
                        $campo = new CampoProcesso;
                        $campo->processo_id = $processo->id;
                        $campo->key = 'text';
                        $campo->name = 'Entidade devedora';
                        $campo->value = (isset($dado[1]) ? trim($dado[1])  : "");
                        $campo->order = 2;
                        $campo->save();
                    }
                    if($dado[0] == "Natureza"){
                        $campo = new CampoProcesso;
                        $campo->processo_id = $processo->id;
                        $campo->key = 'text';
                        $campo->name = 'Natureza';
                        $campo->value = (isset($dado[1]) ? trim($dado[1])  : "");
                        $campo->order = 3;
                        $campo->save();
                    }
                    if($dado[0] == "Data base"){
                    
                        $data = preg_split('/\s+/',$dado[1]);
                        $novadata = explode('/', $data[1]);
                        // $processos->data_base = ;
                        // $taxa = HomeController::extractTaxa($data);
                        // $processos->inicio_data_base_taxa = $taxa;
                        $campo = new CampoProcesso;
                        $campo->processo_id = $processo->id;
                        $campo->key = 'text';
                        $campo->name = 'Data base';
                        $campo->value = trim(date('Y-m-d',strtotime($novadata[2]."-".$novadata[1]."-".$novadata[0])));
                        $campo->order = 4;
                        $campo->save();
                    }
                    if($dado[0] == "Data de nascimento"){
                        $processos = Order::find($request->id);
                        $processos->data = $dado[1];
                        $campo = new CampoProcesso;
                        $campo->processo_id = $processo->id;
                        $campo->key = 'text';
                        $campo->name = 'Data de nascimento';
                        $campo->value = (isset($dado[1]) ? trim($dado[1])  : "");
                        $campo->order = 5;
                        $campo->save();
                    }
                    if($dado[0] == "Requisitado"){
                        if($dado[1] !=  "\tNão informado pelo peticionante\t"){
                            $nvalor = preg_split('/\s+/',$dado[1]);
                            $valor = explode('.', $nvalor[1]);
                            if(count($valor) == 3){

                                $centavos = explode(',',$valor[2]);

                                $newvalor =$valor[0].$valor[1].$centavos[0].".".$centavos[1];

                            }
                            elseif(isset($valor[1])){
                                $centavos = explode(',',$valor[1]);
                                $newvalor =$valor[0].$centavos[0].".".$centavos[1];
                            }else{
                                $centavos = explode(',',$dado[1]);
                                $newvalor =$centavos[0].".".$centavos[1];
                            }
                            if($valor[0] == "0,00"){
                                $newvalor = null;
                            }
                            $campo = new CampoProcesso;
                            $campo->processo_id = $processo->id;
                            $campo->key = 'text';
                            $campo->name = 'Requisitado';
                            $campo->value = (isset($newvalor) ? trim($newvalor)  : "");
                            $campo->order = 6;
                            $campo->save();
                        }else{
                            $campo = new CampoProcesso;
                            $campo->processo_id = $processo->id;
                            $campo->key = 'text';
                            $campo->name = 'Requisitado';
                            $campo->value = null;
                            $campo->order = 6;
                            $campo->save();
                        }
                    }
                    if($dado[0] == "Principal bruto"){
                        if($dado[1] != "\tNão informado pelo peticionante\t"){
                            $nvalor = preg_split('/\s+/',$dado[1]);
                            $valor = explode('.', $nvalor[1]);
                            if(count($valor) == 3){

                                $centavos = explode(',',$valor[2]);

                                $newvalor =$valor[0].$valor[1].$centavos[0].".".$centavos[1];

                            }
                            elseif(isset($valor[1])){
                                $centavos = explode(',',$valor[1]);
                                $newvalor =$valor[0].$centavos[0].".".$centavos[1];
                            }else{
                                $centavos = explode(',',$dado[1]);

                                $newvalor =$centavos[0].".".$centavos[1];


                            }
                            if($valor[0] == "0,00"){
                                $newvalor = null;
                            }
                            $campo = new CampoProcesso;
                            $campo->processo_id = $processo->id;
                            $campo->key = 'text';
                            $campo->name = 'Principal bruto';
                            $campo->value = (isset($newvalor) ? trim($newvalor)  : "");
                            $campo->order = 7;
                            $campo->save();
                        }else{
                            $campo = new CampoProcesso;
                            $campo->processo_id = $processo->id;
                            $campo->key = 'text';
                            $campo->name = 'Principal bruto';
                            $campo->value = null;
                            $campo->order = 7;
                            $campo->save();
                        }
                    }
                    if($dado[0] == "Total da condenação"){
                        if($dado[1] !=  "\tNão informado pelo peticionante\t"){
                            $nvalor = preg_split('/\s+/',$dado[1]);
                            $valor = explode('.', $nvalor[1]);
                            if(count($valor) == 3){

                                $centavos = explode(',',$valor[2]);

                                $newvalor =$valor[0].$valor[1].$centavos[0].".".$centavos[1];

                            }
                            elseif(isset($valor[1])){
                                $centavos = explode(',',$valor[1]);

                                $newvalor =$valor[0].$centavos[0].".".$centavos[1];
                            }else{
                                $centavos = explode(',',$dado[1]);
                                $newvalor =$centavos[0].".".$centavos[1];
                            }
                            $campo = new CampoProcesso;
                            $campo->processo_id = $processo->id;
                            $campo->key = 'text';
                            $campo->name = 'Total da condenação';
                            $campo->value = (isset($newvalor) ? trim($newvalor)  : "");
                            $campo->order = 8;
                            $campo->save();
                        }else{
                            $campo = new CampoProcesso;
                            $campo->processo_id = $processo->id;
                            $campo->key = 'text';
                            $campo->name = 'Total da condenação';
                            $campo->value =  null;
                            $campo->order = 8;
                            $campo->save();
                        }
                    }
                    if($dado[0] == "Juros moratórios"){
                        if($dado[1] !=  "\tNão informado pelo peticionante\t"){
                            $nvalor = preg_split('/\s+/',$dado[1]);
                            $valor = explode('.', $nvalor[1]);
                            if(count($valor) == 3){

                                $centavos = explode(',',$valor[2]);

                                $newvalor =$valor[0].$valor[1].$centavos[0].".".$centavos[1];

                            }
                            elseif(isset($valor[1])){
                                $centavos = explode(',',$valor[1]);
                                $newvalor =$valor[0].$centavos[0].".".$centavos[1];
                            }else{
                                $centavos = explode(',',$dado[1]);
                                $newvalor = $centavos[0].".".$centavos[1];
                            }
                            if($valor[0] == "0,00"){
                                $newvalor = null;
                            }
                            $campo = new CampoProcesso;
                            $campo->processo_id = $processo->id;
                            $campo->key = 'text';
                            $campo->name = 'Juros moratórios';
                            $campo->value = (isset($newvalor) ? trim($newvalor)  : "");
                            $campo->order = 9;
                            $campo->save();
                        }else{
                            $campo = new CampoProcesso;
                            $campo->processo_id = $processo->id;
                            $campo->key = 'text';
                            $campo->name = 'Juros moratórios';
                            $campo->value = (isset($cpf[1]) ? trim($cpf[1])  : "");
                            $campo->order = 9;
                            $campo->save();
                        }
                    }
                }
            }
            return Response()->json(['Sucesso' => true]);
        }
    }
}
    
  
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $processos = Processo::find($request->id);
        $campos = CampoProcesso::where('processo_id',$processos->id)->orderBy('order', 'asc')->get();
        $processo = Processo::count();
        $robos = Robo::count();
        $totalMesAnterior = $this->returnQuantidadeProcesso();  
        $robosOn = Robo::where('status', 1)->count();
        $robosOff = Robo::where('status', 0)->orWhere('status', 2)->count();
        $robosErro = Robo::where('status', 2)->count();
        if($processo > 0 && $totalMesAnterior >0){
            $mesPassado = number_format(($processo / $totalMesAnterior) * 100,2,'.','.');
        }else{
            $mesPassado = "0.00";
        }
        return view('processo.view',compact('processo','mesPassado','processos','campos','robos','robosOff','robosOn','robosErro'));
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
    public function update(Request $request)
    {
         $params = count($request->request) - 2;
         $paramsRequest = $request->all();

         foreach($paramsRequest as $index => $value){
            $param = explode('-',$index);
            if(isset($param[1])){
                $campos = CampoProcesso::find($param[1]);
                if($campos){
                    $campos->value = $value;
                    $campos->save();
                }
            }
         }

         return Redirect()->back()->with('success','Sucesso ao autalizar o processo');
        
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
