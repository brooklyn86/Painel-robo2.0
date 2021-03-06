<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Robos Fair Consultoria</title>
        <!-- Favicon -->
        <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <!-- Icons -->
        <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
        <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
        <!-- Argon CSS -->
        <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
        <link type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
        <!-- <link type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"> -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/sl-1.3.1/datatables.min.css"/>
    </head>
    <script>
        var robo_id = <?php if(isset($robo_id)){ echo $robo_id;}else{ echo 'null';};?>;
        var login = <?php if(isset($login)){ echo $login;}else{ echo 'null';};?>;
    </script>

    <body class="{{ $class ?? 'bg-gradient-danger' }}">
        @auth()
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        
        @endauth
        
        <div class="main-content">
            @include('layouts.navbars.navbar')
            @yield('content')
        </div>


        <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
        <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/js/jquery.mask.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/sl-1.3.1/datatables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        @if(session('success'))
   
            <script>
                swal("Completo!", "{{session('success')}}", "success");
            </script>
        @endif
        @if(session('error'))
   
            <script>
                swal("Falha!", "{{session('error')}}", "danger");
            </script>
        @endif
        <input type="hidden" id="reloadTable"/>
        <script>
         $(document).ready(function() {
            
            $("#processoViewModal").on('shown.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var id_processo = button.data("processo");
                $("#body_processo").html('<img src="/argon/img/Infinity-1s-200px.gif" width="100%"></img>');

                $.ajax({
                    url: "/processos/"+id_processo+"/view", success: function(result){
                    $("#body_processo").html(result);
                }});
            });

            $("#processoAcordoViewModal").on('shown.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var id_processo = button.data("processo");
                $("#body_processoAcordo").html('<img src="/argon/img/Infinity-1s-200px.gif" width="100%"></img>');

                $.ajax({
                    url: "/processos/view/acordos/"+id_processo+"/processo", success: function(result){
                    $("#body_processoAcordo").html(result);
                }});
            });
            $("#editarUsuario").on('shown.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var id_processo = button.data("processo");
                $("#body_processoUsuario").html('<img src="/argon/img/Infinity-1s-200px.gif" width="100%"></img>');

                $.ajax({
                    url: "/getUsuario/"+id_processo, success: function(result){
                    $("#body_processoUsuario").html(result);
                }});
            });
            $('#filter').mask("#.##0,00", {reverse: true});
            var processo = $('#processo').DataTable({
                language : {
                "sEmptyTable": "Nenhum processo encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ processos",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 processos",
                "sInfoFiltered": "(Filtrados de _MAX_ processos)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ processos por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum processo encontrado",
                "sSearch": "Pesquisar processo",
                "oPaginate": {
                    "sNext": "»",
                    "sPrevious": "«",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                },
                "select": {
                    "rows": {
                        "_": "%d processos selecionados",
                        "0": "Nenhum processo selecionado",
                        "1": "1 processo selecionado "
                    }
                },
                "buttons": {
                    "copy": "Copiar para a área de transferência",
                    "copyTitle": "Cópia bem sucedida",
                    "copySuccess": {
                        "1": "Uma linha copiada com sucesso",
                        "_": "%d linhas copiadas com sucesso"
                    }
                }
            },
            processing: true,
            serverSide: true,
            dom: 'Bfrtip',
            select: true,
            select: {
                style:    'multi',
                selector: 'td:first-child'
            },
            // buttons: [
            // {
            //     text: 'Enviar Todos',
            //     className: 'btn btn-success',
            //     action: function ( e, dt, items) {
            //         var process = [];
            //         var data = processo.rows({ selected: true }).data();
            //         data.each(function( index ) {
            //             process.push(index);
            //         });
            //         var pp = JSON.stringify(process);
            //         $.post("{{route('submit.processo.api')}}", {data: pp}, function(data, status){
                      
            //         });
                  
            //     }
            // }
        // ],
            ajax: '/processos/getprocessosDatatables/'+robo_id,
            columns: [
                {
                    data : 'id',
                    name : 'id'
                },                
                {
                    data : 'processo',
                    name : 'processo'
                },
                {
                    data : 'robo',
                    name : 'robo'
                },
                {
                    data : 'actions',
                    name : 'actions'
                },

            ]
        });
        setInterval( function () {
            processo.ajax.reload( null, false );
        }, 30000  );

        $("#filter").on('blur',function(e){
            var filter = $(e.currentTarget).val();
            processo.ajax.url('/processos/getprocessosDatatables/'+robo_id+'?filter='+filter);
            if(filter.length > 3){
                processo.ajax.reload( null, false );
            }else{
                swal("Desculpe!", "Informe o valor maior que 3 digitos", "error");
            }
        });

        $("#limparFiltro").on('click',function(e){
            $('#filter').val('');
            processo.ajax.url('/processos/getprocessosDatatables/'+robo_id);
            processo.ajax.reload( null, false );
        });
        $('#reloadTable').on('click', function(){
            processo.ajax.reload( null, false );
        });
        } );
        </script>
        <script>
        $(document).ready(function() {
            var situacao = $('#situacao').DataTable({
            language : {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ Resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar Precatoria",
                "oPaginate": {
                    "sNext": "»",
                    "sPrevious": "«",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                },
                "select": {
                    "rows": {
                        "_": "%d precatorias selecionadas",
                        "0": "Nenhuma precatoria selecionada",
                        "1": "1 precatoria selecionado "
                    }
                },
                "buttons": {
                    "copy": "Copiar para a área de transferência",
                    "copyTitle": "Cópia bem sucedida",
                    "copySuccess": {
                        "1": "Uma linha copiada com sucesso",
                        "_": "%d linhas copiadas com sucesso"
                    }
                }
            },
            processing: true,
            serverSide: true,
            ajax: '/processos/get/situacao/processual/'+login,
            columns: [
                {
                    data : 'id',
                    name : 'id'
                },
                {
                    data : 'precatoriaStatus',
                    name : 'precatoriaStatus'
                },
                {
                    data : 'actions',
                    name : 'actions'
                },

            ]
        });
        setInterval( function () {
            situacao.ajax.reload( null, false );
        }, 30000  );
        } );
        $(document).ready(function() {
            var usuarios = $('#usuarios').DataTable({
            language : {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ Resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar usuario",
                "oPaginate": {
                    "sNext": "»",
                    "sPrevious": "«",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                },
                "select": {
                    "rows": {
                        "_": "%d usuarios selecionados",
                        "0": "Nenhum robô selecionado",
                        "1": "1 usuario selecionado "
                    }
                },
                "buttons": {
                    "copy": "Copiar para a área de transferência",
                    "copyTitle": "Cópia bem sucedida",
                    "copySuccess": {
                        "1": "Uma linha copiada com sucesso",
                        "_": "%d linhas copiadas com sucesso"
                    }
                }
            },
            processing: true,
            serverSide: true,
            ajax: '{{ route('get.usuarios') }}',
            columns: [
                {
                    data : 'name',
                    name : 'name'
                },
                {
                    data : 'email',
                    name : 'email'
                },
                {
                    data : 'permissao',
                    name : 'permissao'
                },
                {
                    data : 'actions',
                    name : 'actions'
                },

            ]
        });
        setInterval( function () {
            usuarios.ajax.reload( null, false );
        }, 30000  );
        } );
        $(document).ready(function() {
            var robo = $('#robo').DataTable({
            language : {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ Resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar Robô",
                "oPaginate": {
                    "sNext": "»",
                    "sPrevious": "«",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                },
                "select": {
                    "rows": {
                        "_": "%d robos selecionados",
                        "0": "Nenhum robô selecionado",
                        "1": "1 robô selecionado "
                    }
                },
                "buttons": {
                    "copy": "Copiar para a área de transferência",
                    "copyTitle": "Cópia bem sucedida",
                    "copySuccess": {
                        "1": "Uma linha copiada com sucesso",
                        "_": "%d linhas copiadas com sucesso"
                    }
                }
            },
            processing: true,
            serverSide: true,
            ajax: '{{ route('get.bots') }}',
            columns: [
                {
                    data : 'name',
                    name : 'name'
                },
                {
                    data : 'processos',
                    name : 'processos'
                },
                {
                    data : 'description',
                    name : 'description'
                },
                {
                    data : 'statusForm',
                    name : 'statusForm'
                },
                {
                    data : 'actions',
                    name : 'actions'
                },

            ]
        });
        setInterval( function () {
            robo.ajax.reload( null, false );
        }, 30000  );
        } );
        </script>
        
        @stack('js')
        
        <!-- Argon JS -->
        <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>
    </body>
</html>