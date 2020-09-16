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

    <script src='/calendar/main.js'></script>
    <script src='/calendar/locales/pt-br.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="/vendor/toastr/toastr.min.js"></script>
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
                        <!--div class="form-group row">
                            <label class="col-sm-2 col-form-label">Color</label>
                            <div class="col-sm-10">
                                <select name="color" class="form-control" id="color">
                                    <option value="">Selecione</option>
                                    <option style="color:#FFD700;" value="#FFD700">Amarelo</option>
                                    <option style="color:#0071c5;" value="#0071c5">Azul Turquesa</option>
                                    <option style="color:#FF4500;" value="#FF4500">Laranja</option>
                                    <option style="color:#8B4513;" value="#8B4513">Marrom</option>
                                    <option style="color:#1C1C1C;" value="#1C1C1C">Preto</option>
                                    <option style="color:#436EEE;" value="#436EEE">Royal Blue</option>
                                    <option style="color:#A020F0;" value="#A020F0">Roxo</option>
                                    <option style="color:#40E0D0;" value="#40E0D0">Turquesa</option>
                                    <option style="color:#228B22;" value="#228B22">Verde</option>
                                    <option style="color:#8B0000;" value="#8B0000">Vermelho</option>
                                </select>
                            </div>
                        </div-->
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

<style>
    .fc-day-past {
        background-color: rgba(255, 159, 137, 0.16);
    }

    .fc-day-future {
        background-color: rgb(143, 223, 130, 0.16);
    }

    .fc-daygrid-event {
        background-color: rgba(174, 214, 241, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.3);
    }
</style>
<script>
    const baseUrl = '<?= url('') ?>';

    $('#form-event').submit(function() {
        var date = $('#cadastrar #start').val();
        var time = $('#time').val();
        var datetime = date + ' ' + time;
        $('#datetime').val(datetime);
    });
    /* Função que exibe mensagens de erros e sucesso quanto em contato com uma função PHP */
    <?php if (count($errors) > 0) : ?>
        var temp = '';
        $('#msg').modal('show');
        $('#span-msg').addClass('text-danger');
        <?php foreach ($errors->all() as $error) : ?>
            temp += '<p class="mb-0">{{$error}}</p>';
        <?php endforeach; ?>
        $('#span-msg').html(temp);
    <?php elseif (session('success')) : ?>
        $('#msg').modal('show');
        $('#span-msg').addClass('text-success');
        $('#span-msg').html('{{ session("success") }}');
    <?php endif; ?>
</script>
<script>
    toastr.options = {
        "closeButton": true,
        "preventDuplicates": true,
        "progressBar": true,
    }
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
            eventSources: [
                baseUrl + '/calendar/selectEvents',
            ],
            events: baseUrl + '/calendar/getNonOperatingDays',
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: false
            },
            eventColor: '#378006',
            locale: "pt-br",
            editable: true,
            dayMaxEventRows: true,
            hiddenDays: [0],
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listMonth'
            },
            navLinks: false, // can click day/week names to navigate views
            selectable: true,
            editable: false,


            eventClick: function(info) {
                info.jsEvent.preventDefault(); // don't let the browser navigate

                $("#visualizar #id").text(info.event.id);
                $("#visualizar #title").text(info.event.title);
                $("#visualizar #start").text(info.event.start.toLocaleString());
                $("#visualizar #end").text(info.event.end.toLocaleString());
                $("#visualizar").modal("show");
            },
        });

        calendar.render();
    });
</script>

</html>