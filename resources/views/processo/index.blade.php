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
