<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Argon Dashboard') }}</title>
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
        <link type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    </head>
    <script>
        var robo_id = <?php if(isset($robo_id)){ echo $robo_id;}else{ echo 'null';};?>
    </script>
    <body class="{{ $class ?? '' }}">
        @auth()
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @include('layouts.navbars.sidebar')
        @endauth
        
        <div class="main-content">
            @include('layouts.navbars.navbar')
            @yield('content')
        </div>


        <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
        <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>


        <script>
         $(document).ready(function() {
            var processo = $('#processo').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/processos/getprocessosDatatables/'+robo_id,
            columns: [
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
        } );
        </script>
        <script>

        $(document).ready(function() {
            var robo = $('#robo').DataTable({
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