<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 text-white text-uppercase d-none d-lg-inline-block" href="{{ route('home') }}">{{ __('Inicio') }}</a>
        @if(auth()->user()->role_id == 1)
            <a class="h4 text-white text-uppercase d-none d-lg-inline-block" href="{{ route('process.situacao') }}">{{ __('Situação Processual') }}</a>
        @endif

        <!-- Form -->
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm  font-weight-bold">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Painel de controle') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('Perfil') }}</span>
                    </a>
                    @if(auth()->user()->role_id == 1)

                    <a href="/user" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('Usuarios') }}</span>
                    </a>
                    @endif

                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Sair') }}</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>