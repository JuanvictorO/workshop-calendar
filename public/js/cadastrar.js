// Adiciona um valor no campo DATETIME ao enviar o formulário.
$('#form-event').submit(function () {
    var date = $('#cadastrar #start').val();
    var time = $('#time').val();
    var datetime = date + ' ' + time;
    $('#datetime').val(datetime);
});

// FULL CALENDAR
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        // Exibe os dias de Não Funcionamento
        events: baseUrl + '/calendar/getNonOperatingDays',
        //Configurações do calendar
        locale: "pt-br",
        editable: true,
        dayMaxEventRows: true,
        hiddenDays: [0],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        navLinks: false,
        selectable: false,
        /*selectAllow: function(selectInfo) {
            if (dataAtual < selectInfo.startStr) {
                return false;
            } else {
                return true;
            }
        },*/
        
        // Verifica se o dia selecionado está livre para marcar uma consulta e, se sim, exibe o modal com os horários disponíveis 
        dateClick: function (info) {

            var events = calendar.getEvents();
            var result = verifyEventInDay(events, info.dateStr);

            if (info.dateStr < dataAtual) {
                toastr["warning"]("Você não pode marcar a consulta nesse dia!");
            } else if (result) {
                toastr["warning"]("Nosso estabelecimento não funcionará esse dia!");
            } else {
                var time = info.dateStr;

                $.ajax({
                    data: '',
                    type: 'GET',
                    dataType: 'JSON',
                    url: baseUrl + '/calendar/getEventsInDate/' + time,
                    async: true,
                    success: function ({
                        success,
                        json
                    }) {
                        if (success === true) {
                            $('#cadastrar #time').empty();
                            $("#cadastrar #time").append(json);
                            $("#cadastrar #start").val(info.dateStr.toLocaleString());
                            $("#cadastrar").modal("show");
                        } else {
                            $(info.dayEl).addClass('disabled');
                            $(info.dayEl).css('background-color', 'rgb(255, 159, 137)');
                            toastr['warning'](json);
                        }
                    },
                    error: function () {
                        toastr['error']('Algo deu errado, tente novamente mais tarde ou contate-nos');
                    }
                });
            }
        },
    });

    calendar.render();
});

// Verifica se existe algum evento cadastrado no mesmo dia que a data passada
function verifyEventInDay(events, date) {
    if (events.length == 0) {
        return false;
    }

    var x = 0;
    $.each(events, function (i, val) {
        if (val.startStr == date) {
            x = 1;
            return false;
        }
    });

    if (x == 1) {
        return true;
    } else {
        return false;
    }
}