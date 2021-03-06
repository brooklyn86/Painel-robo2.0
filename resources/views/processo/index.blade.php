@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <div class="container-fluid mt--7">
            <div class="col-xl-12">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Processos <code>{{$robo->name}}</code></h3>
                            </div>
                            <div class="col text-right">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive col-md-12">
                    <div class="alert alert-default alert-dismissible fade show" role="alert">
                        <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                        <span class="alert-text">
                            <strong>Dica!</strong> 
                            Você pode informar o numero do processo para efetuar uma pesquisa!
                            </br>
                            Você tambem pode ordenar a tabela de acordo com a coluna ex: Ordenar por numero de processo, basta clicar na coluna processo, mesma coisa nas outras colunas.
                        </span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <table>
                        <tr>
                            <td>
                                <input name='filter' class="form-control" placeholder="Filtro de valor" id="filter"></input>
                            <td>
                            <td>
                                <button style="float:left" class="btn btn-info">Filtrar</button>
                            </td>
                            <td>
                                <button id="limparFiltro" class="btn btn-warning">Limpar filtro</button>
                            </td>
                        <tr>
                    </table>
                        <!-- Projects table -->
                        <table id="processo" class="table align-items-center table-flush">
                            
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Processo</th>
                                    <th scope="col">Robo</th>
                                    <th scope="col">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="processoViewModal" tabindex="-1" role="dialog" aria-labelledby="processoViewModal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content bg-gradient-dark">
                    
                        <div class="modal-body" id="body_processo">
                            
                        <img src="/argon/img/Infinity-1s-200px.gif" width="80%"></img>
                            
                        </div>
                    
                        
                    </div>
                </div>
            </div>

@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush