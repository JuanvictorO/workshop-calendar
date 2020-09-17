<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="description" content="#">
    <meta name="author" content="Julia Bussinger - Github: https://github.com/jubussinger | Juan Victor Oliveira - Github: https://github.com/JuanvictorO">
    <link rel="shortcut icon" type="image/png" href="#">
    <title>Workshop-calendar</title>

    <meta name="csrf-token" content="{{csrf_token ()}}">

    <link href="{{ asset('/vendor/calendar/main.css') }}" rel='stylesheet'>

    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-4.4.1/bootstrap.min.css') }}">

    <!-- Css template -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap">
    <link rel="stylesheet" href="{{ asset('/css/theme.min.css') }}">

    <link rel="stylesheet" href="{{ asset('/vendor/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/jquery-confirm/css/jquery-confirm.css') }}">

    <!-- Aqui estarão os links stylesheet a serem passados dentro da view -->
    @stack('link')

</head>

<body>
    <!-- Input para armazenar a Base Url, seu valor é utilizado no main.js -->
    <input type="hidden" id="baseUrl" value="{{ url('') }}">

    <div class="page-wrapper">

        <!-- Header -->
        <header style="background-color:#004b8d">
            <div class="container">
                <div class="header4-wrap ">
                    <div class="pl-4 header__logo">
                        <a href="#">
                            <img width="50%" class="img-fluid py-2" src="{{ asset('/img/logo.png') }}" alt="">
                        </a>
                    </div>
                    <div class="text-light pr-2 header__tool">
                        <strong class="mr-2">Conectado como:</strong> juan.oliveira@groove.com.br
                    </div>
                </div>
            </div>
        </header>

        <!-- Barra menu lateral esquerda -->
        <div class="pt-5 ">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <aside class="menu-sidebar3 js-spe-sidebar">
                                <nav class="navbar-sidebar2 navbar-sidebar3">
                                    <ul class="list-unstyled navbar__list">
                                        <li>
                                            <a href="{{ url('/cadastrar') }}"><i class="far fa-calendar-plus"></i>Agendar Avaliação</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/nonOperatingDays') }}"><i class="fas fa-calendar-times"></i>Adicionar feriados</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/listar') }}"><i class="far fa-calendar-alt"></i>Visualizar Avaliações</a>
                                        </li>
                                        <li>
                                            <a class="text-danger" href="#"><i class="fas fa-power-off"></i>Sair</a>
                                        </li>
                                    </ul>
                                </nav>
                            </aside>
                        </div>
                        <div class="col-xl-9 col-md-9">
                            <div class="page-content">
                                <!-- Aqui será o conteúdo da página a ser exibido -->
                                @yield('content')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="copyright">
                                            <p>
                                                Copyright © <?= gmdate('Y', time()) . '  Workshop-calendar' ?>. Todos direitos reservados Produzido por: <a target="_blank" href="#"><img width="80%" src="{{ asset('/img/producer.png') }}"></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

</body>

<!--- MODAL SUCCESS OR ERROR --->
<div class="modal fade" id="msg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content py-4">
            <div class="modal-body text-center">
                <span id="span-msg"></span><br>
                <span class="text-secondary">Clique fora da caixa para fechar</span>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('/vendor/calendar/main.js') }}"></script>
<script src="{{ asset('/vendor/calendar/locales/pt-br.js') }}"></script>

<script src="{{ asset('/vendor/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('/vendor/bootstrap-4.4.1/popper.min.js') }}"></script>
<script src="{{ asset('/vendor/bootstrap-4.4.1/bootstrap.min.js') }}"></script>

<script src="{{ asset('/vendor/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('/vendor/jquery-confirm/js/jquery-confirm.js') }}"></script>

<script src="{{ asset('/js/main.min.js') }}"></script>
<!-- Aqui estarão os scripts a serem passados na view -->
@stack('script')

</html>