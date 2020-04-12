@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <div class="container-fluid mt--7">
            <div class="col-xl-12">
                <div class="card shadow">
                    <form method="post" action="{{route('create.bot.post')}}">
                    @csrf
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Cadastrar novo robo</h3>
                            </div>
                            <div class="col text-right">
                                <a href="/home" class="btn btn-sm btn-danger">Cancelar</a>
                                <button type="submit" class="btn btn-sm btn-success">Cadastrar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name" class="form-control-label">Nome</label>
                            <input class="form-control" name="name" type="text" id="name">
                        </div>
                        <div class="form-group">
                            <label for="descricao" class="form-control-label">Descrição</label>
                            <textarea class="form-control" name="description" id="descricao"></textarea>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

         
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush