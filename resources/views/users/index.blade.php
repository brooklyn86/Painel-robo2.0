@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Listagem de Usuarios</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#newUsuario">Novo Usuario</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                                        </div>

                <div class="table-responsive">
                    <table id="usuarios" class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Permissão</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                                                                <tr>
                                    <td>Admin Admin</td>
                                    <td>
                                        <a href="mailto:admin@argon.com">admin@argon.com</a>
                                    </td>
                                    <td>Administrador</td>
                                    <td class="text-right">
                                        <button class="btn btn-sm btn-dark">Editar</button>
                                    </td>
                                </tr>
                                                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="newUsuario" tabindex="-1" role="dialog" aria-labelledby="processoAcordoViewModal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content bg-gradient-dark">

                        <div class="modal-body" id="body_processoAcordo">
                            
                        
                            
                        </div>
                    
                        
                    </div>
                </div>
            </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush

