@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <div class="container-fluid mt--7">
            <div class="col-xl-12">
            <form method="post" action="{{route('atualiza.processo')}}">

                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Dados do Processo <code>{{$processos->processo}}</code></h3>
                            </div>
                            <div class="col text-right">
                                <a href="/processos/view/robo/{{$processos->robo_id}}/processos" class="btn btn-sm btn-danger">Cancelar</a>
                                <button type="submit" class="btn btn-sm btn-success">Atualizar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                    <div class="row">
                    @csrf
                    
                    @foreach($campos as $campo)
                      <!-- Campo TEXT -->
                      @if($campo->key == 'text')
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">{{$campo->name}}</label>
                            <input type="text" class="form-control form-control-alternative" id="{{$campo->name}}-{{$campo->id}}" name="{{$campo->name}}-{{$campo->id}}" placeholder="{{$campo->name}}" value ="{{$campo->value}}">
                        </div>
                      </div>
                      @endif
                      @if($campo->key == 'text-disable')
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">{{$campo->name}}</label>
                            <input type="text" class="form-control form-control-alternative" id="{{$campo->name}}-{{$campo->id}}" name="{{$campo->name}}-{{$campo->id}}" placeholder="{{$campo->name}}" value ="{{$campo->value}}" disabled>
                        </div>
                      </div>
                      @endif
                      @if($campo->key == 'numerico')
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">{{$campo->name}}</label>
                            <input type="number" class="form-control form-control-alternative" id="{{$campo->name}}-{{$campo->id}}" name="{{$campo->name}}-{{$campo->id}}" placeholder="{{$campo->name}}" value ="{{$campo->value}}">
                        </div>
                      </div>
                      @endif
                      @if($campo->key == 'numerico-disable')
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">{{$campo->name}}</label>
                            <input type="number" class="form-control form-control-alternative" id="{{$campo->name}}-{{$campo->id}}" name="{{$campo->name}}-{{$campo->id}}" placeholder="{{$campo->name}}" value ="{{$campo->value}}" disabled>
                        </div>
                      </div>
                      @endif
                      @if($campo->key == 'data')
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">{{$campo->name}}</label>
                            <input type="date" class="form-control form-control-alternative" id="{{$campo->name}}-{{$campo->id}}" name="{{$campo->name}}-{{$campo->id}}" placeholder="{{$campo->name}}" value ="{{$campo->value}}" >
                        </div>
                      </div>
                      @endif
                      @if($campo->key == 'data-disable')
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">{{$campo->name}}</label>
                            <input type="date" class="form-control form-control-alternative" id="{{$campo->name}}-{{$campo->id}}" name="{{$campo->name}}-{{$campo->id}}" placeholder="{{$campo->name}}" value ="{{$campo->value}}" disabled>
                        </div>
                      </div>
                      @endif
                      @if($campo->key == 'radiobutton')
                      <?php $values = explode(',', $campo->name);?>
                      <div class="col-md-6">
                      <div class="form-group">

                        <?php $i = 1;?>
                       @foreach($values as $value)
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="{{$campo->name}}-{{$campo->id}}-n{{$i}}" name="{{$campo->name}}-{{$campo->id}}" value="{{$value}}" class="custom-control-input" <?php if($value == $campo->value){ echo 'checked';}?>>
                                <label class="custom-control-label" for="{{$campo->name}}-{{$campo->id}}-n{{$i}}">{{ucfirst($value)}}</label>
                            </div>
                            <?php $i++;?>
                       @endforeach 
                       </div>
                       </div>

                      @endif
                      @if($campo->key == 'radiobutton-disable')
                      <?php $name = explode('-', $campo->name);?>
                      <?php $values = explode(',', $name[1]);?>
                      <div class="col-md-6">
                      <div class="form-group">

                        <?php $i = 1;?>
                        <label>{{$name[0]}}</label>

                       @foreach($values as $value)
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="{{$campo->name}}-{{$campo->id}}-n{{$i}}" name="{{$campo->name}}-{{$campo->id}}" value="{{$value}}" class="custom-control-input" <?php if($value == $campo->value){ echo 'checked';}?> disabled>
                                <label class="custom-control-label" for="{{$campo->name}}-{{$campo->id}}-n{{$i}}">{{ucfirst($value)}}</label>
                            </div>
                            <?php $i++;?>
                       @endforeach 
                       </div>
                       </div>

                      @endif
  
                      <!-- Campo TEXTAREA -->
                      @if($campo->key == 'textarea')
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">{{$campo->name}}</label>
                            <textarea class="form-control" id="{{$campo->name}}-{{$campo->id}}" name="{{$campo->name}}-{{$campo->id}}" placeholder="{{$campo->name}}">{{$campo->value}}</textarea>
                        </div>
                      </div>
                      @endif
                      @if($campo->key == 'textarea-disable')
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">{{$campo->name}}</label>
                            <textarea type="text" class="form-control" id="{{$campo->name}}-{{$campo->id}}" name="{{$campo->name}}-{{$campo->id}}" placeholder="{{$campo->name}}" disabled>{{$campo->value}}</textarea>
                        </div>
                      </div>
                      @endif
                      @if($campo->key == 'info')
                        <div class="alert alert-secondary alert-dismissible fade show col-md-6" role="alert">
                            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                            <span class="alert-text"><strong>Informativo!</strong> </br>{{$campo->value}}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                      @endif
                      @if($campo->key == 'info-success')
                      <div class="alert alert-dark alert-dismissible fade show col-md-6" role="alert">
                            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                            <span class="alert-text"><strong>Informativo!</strong> </br>{{$campo->value}}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                      @endif
                      @if($campo->key == 'info-warning')
                      <div class="alert alert-warning alert-dismissible fade show col-md-6" role="alert">
                            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                            <span class="alert-text"><strong>Informativo!</strong> </br>{{$campo->value}}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                      @endif
                      @if($campo->key == 'info-danger')
                      <div class="alert alert-danger alert-dismissible fade show col-md-6" role="alert">
                            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                            <span class="alert-text"><strong>Informativo!</strong> </br>{{$campo->value}}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                      @endif
                    @endforeach
                    </div>
                </div>

                <input type="hidden" value="{{$processos->id}}" name="processo_id">
            </form>
            </div>
        </div>

         
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush