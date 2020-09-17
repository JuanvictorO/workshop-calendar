<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{csrf_token ()}}">

    <link href="{{ asset('/vendor/calendar/main.css') }}" rel='stylesheet'>

    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-4.4.1/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('/css/personalizado.css') }}">

    <link rel="stylesheet" href="{{ asset('/vendor/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/jquery-confirm/css/jquery-confirm.css') }}">

    <!-- Aqui estarão os links stylesheet a serem passados dentro da view -->
    @stack('link')

</head>

<body>
    <!-- Input para armazenar a Base Url, seu valor é utilizado no main.js -->
    <input type="hidden" id="baseUrl" value="{{ url('') }}">

    <!-- Aqui será o conteúdo da página a ser exibido -->
    @yield('content')

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