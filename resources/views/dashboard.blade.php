@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <div class="container-fluid mt--7">
            <div class="col-xl-12">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Robos</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{{route('bot.create')}}" class="btn btn-sm btn-primary">Cadastrar Robô</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive col-md-12">
                    <div class="alert alert-default alert-dismissible fade show" role="alert">
                        <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                        <span class="alert-text">
                            <strong>Dica!</strong> 
                            Você pode informar o tipo do robo, nome do robo, quantidade de processo no campo de <b>pesquisar robô</b> para efetuar uma pesquisa!
                            </br>
                            Você tambem pode ordenar a tabela de acordo com a coluna ex: Ordenar por nome, basta clicar na coluna nome, mesma coisa nas outras colunas.
                        </span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <!-- Projects table -->
                        <table id="robo" class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Quantidade de Processos</th>
                                    <th scope="col">Descrição do Robô</th>
                                    <th scope="col">Status</th>
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

         
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush