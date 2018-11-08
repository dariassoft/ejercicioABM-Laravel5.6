<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Ejercicio ABM para Alliansys">
    <meta name="author" content="Daniel Esteban Arias dariassoft@gmail.com">
    <meta name="keyword" content="Ejercicio ABM para Alliansys por Daniel Esteban Arias dariassoft@gmail.com">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Ejercicio ABM para Alliansys por Daniel Esteban Arias dariassoft@gmail.com</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icons -->
    <link href="css/plantilla.css" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<div id="app">
    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>

        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="img/avatars/6.jpg" class="img-avatar" alt="dariassoft@gmail.com">
                    <span class="d-md-down-none">{{Auth::user()->usuario}} </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Cuenta</strong>
                    </div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-lock"></i> Cerrar sesión</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        </ul>
    </header>

    <div class="app-body">

    @if(Auth::check())
        @if (Auth::user()->idrol == 1)
            @include('plantilla.sidebaradministrador')
        @elseif (Auth::user()->idrol == 2)
            @include('plantilla.sidebarusuario')
        @else

        @endif

    @endif
    <!-- Contenido Principal -->
    @yield('contenido')
    <!-- /Fin del contenido principal -->
    </div>
</div>
    <footer class="app-footer">
        <span><a href="">Daniel Esteban Arias</a> &copy; 2018</span>
        <span class="ml-auto">Desarrollado por <a href="http://www.dariassoft.com.ar/">DariasSoft</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
</body>

</html>