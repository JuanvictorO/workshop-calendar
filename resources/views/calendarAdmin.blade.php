@extends('admin-template.dashboard')

@push('link')
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
@endpush

@section('content')
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
@endsection

@push('script')
<script>
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

    //  FULL CALENDAR
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            // Exibe os eventos cadastrados
            eventSources: [
                baseUrl + '/calendar/selectEvents',
            ],
            // Exibe os dias de Não Funcionamento
            events: baseUrl + '/calendar/getNonOperatingDays',
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: false
            },
            //Configurações do calendar
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
            navLinks: false,
            selectable: true,
            editable: false,
            // Exibe as informações do evento clicado.
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
@endpush