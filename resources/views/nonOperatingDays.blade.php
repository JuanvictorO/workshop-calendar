<!--**
 * @author Cesar Szpak - Celke - cesar@celke.com.br
 * @pagina desenvolvida usando FullCalendar e Bootstrap 4,
 * o código é aberto e o uso é free,
 * porém lembre-se de conceder os créditos ao desenvolvedor.
 *-->
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8' />
    <meta name="csrf-token" content="{{csrf_token ()}}">
    <link href='/calendar/main.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/personalizado.css">

    <link rel="stylesheet" href="/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="/vendor/jquery-confirm/css/jquery-confirm.css">

    <script src='/calendar/main.js'></script>
    <script src='/calendar/locales/pt-br.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src="/vendor/toastr/toastr.min.js"></script>
    <script src="/vendor/jquery-confirm/js/jquery-confirm.js"></script>
    <!--script src="/js/personalizado.js"></script-->
</head>

<body>
    <div id='calendar'></div>

    <div class="modal fade" id="visualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detalhes do Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <dl class="row">
                        <dt class="col-sm-3">ID do evento</dt>
                        <dd class="col-sm-9" id="id"></dd>

                        <dt class="col-sm-3">Título do evento</dt>
                        <dd class="col-sm-9" id="title"></dd>

                        <dt class="col-sm-3">Início do evento</dt>
                        <dd class="col-sm-9" id="start"></dd>

                        <dt class="col-sm-3">Fim do evento</dt>
                        <dd class="col-sm-9" id="end"></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cadastrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-event" method="post" action="{{ url('register') }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nome</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="Nome" placeholder="Nome" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Data</label>
                            <div class="col-sm-10">
                                <input type="date" name="start" class="form-control" id="start" readonly="readonly">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Horários Disponíveis</label>
                            <div class="col-sm-10">
                                <select name="time" class="form-control" id="time" required>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 text-right">
                                <input id="datetime" type="hidden" name="datetime">
                                <input type="submit" value="Cadastrar" class="btn btn-success">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
<script>
    toastr.options = {
        "closeButton": true,
        "preventDuplicates": true,
        "progressBar": true,
    }
</script>

<script>
    const baseUrl = '<?= url('') ?>';
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        let data = new Date();
        let data2 = new Date(data.valueOf() - data.getTimezoneOffset() * 60000);
        var dataBase = data2.toISOString().replace(/\.\d{3}Z$/, "");
        dataBase = dataBase.split("T");
        const dataAtual = dataBase[0];

        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: "pt-br",
            editable: true,
            dayMaxEventRows: true,
            hiddenDays: [0],
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            navLinks: false, // can click day/week names to navigate views
            selectable: true,
            editable: false,

            events: baseUrl + '/calendar/getNonOperatingDays',

            dateClick: function(info) {

                var events = calendar.getEvents();
                var result = verifyEventInDay(events, info.dateStr);

                if (info.dateStr < dataAtual) {
                    toastr["warning"]("Essa data já é passada, escolha uma data futura para não funcionamento.");

                } else if (result.bool) {
                    $.confirm({
                        title: 'Tem certeza?',
                        content: 'Clicando em confirmar você removerá o dia: ' + info.dateStr + ' da lista de dias de Não Funcionamento!',
                        buttons: {
                            confirmar: function() {
                                console.log(result);
                                ajaxDelete(result.id);
                            },
                            cancelar: function() {},
                        }
                    });
                } else {

                    confirmDay(info.dateStr);
                }
            },
            eventClick: function(info) {
                alert('okay');
            }
        });

        calendar.render();
    });

    function confirmDay(date) {
        date = date.toLocaleString();
        $.confirm({
            title: 'Adicionar dia de não funcionamento',
            content: 'Tem certeza que deseja escolher o dia ' + date + ' como não funcionamento?',
            buttons: {
                confirmar: function() {
                    $.ajax({
                        data: {
                            start: date,
                            _token: '{{csrf_token()}}'
                        },
                        type: 'POST',
                        url: baseUrl + '/calendar/addNonOperatingDay',
                        async: true,
                        success: function(response) {
                            if (response.success === true) {
                                toastr['success'](response.msg);
                                location.reload();
                            } else {
                                toastr['warning'](response.msg);
                            }
                        },
                        error: function() {
                            toastr['error']('Algo deu errado, tente novamente mais tarde ou contate-nos');
                        },
                        dataType: 'json'
                    });
                },
                cancelar: function() {},
            }
        });
    }

    function verifyEventInDay(events, date) {
        if (events.length == 0) {
            var json = {
                "bool": false
            };
            return json;
        }

        var x = 0;
        var id = '';
        $.each(events, function(i, val) {
            console.log(val);
            if (val.startStr == date) {
                x = 1;
                id = parseInt(val.id);
                return false;
            }
        });

        if (x == 1) {
            var json = {
                "bool": true,
                "id": id
            };
            return json;
        } else {
            var json = {
                "bool": false
            };
            return json;
        }
    }

    function ajaxDelete(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            data: '',
            type: 'DELETE',
            dataType: 'JSON',
            url: baseUrl + '/calendar/deleteNonOperatingDay/' + id,
            async: true,
            success: function(response) {
                if (response.success === true) {
                    toastr['success'](response.msg);
                    location.reload();
                } else {
                    toastr['warning'](response.msg);
                }
            },
            error: function() {
                toastr['error']('Algo deu errado, tente novamente mais tarde ou contate-nos');
            },
        });
    }
</script>

</html>