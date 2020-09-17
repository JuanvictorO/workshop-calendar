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
        selectable: true,
        editable: false,

        /* 
        Verifica se o dia selecionado possui um evento. 
        Se sim, é criado um alerta perguntado se o admin quer desmarcar este dia como Dia Não Funcional 
        Se não, é criado um alerta perguntado se o admin deseja adicionar esse dia em Dias Não Funcionais 
        */
        dateClick: function (info) {
            info.jsEvent.preventDefault(); // don't let the browser navigate

            var events = calendar.getEvents();
            var result = verifyEventInDay(events, info.dateStr);

            if (info.dateStr < dataAtual) {
                toastr["warning"]("Essa data já é passada, escolha uma data futura para não funcionamento.");
            } else if (result.bool) {
                $.confirm({
                    title: 'Tem certeza?',
                    content: 'Clicando em confirmar você removerá o dia: ' + info.dateStr + ' da lista de dias de Não Funcionamento!',
                    buttons: {
                        confirmar: function () {
                            console.log(result);
                            ajaxDelete(result.id);
                        },
                        cancelar: function () { },
                    }
                });
            } else {
                confirmDay(info.dateStr);
            }
        },
        eventClick: function (info) {
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
            confirmar: function () {
                $.ajax({
                    data: {
                        start: date,
                        _token: token
                    },
                    type: 'POST',
                    url: baseUrl + '/calendar/addNonOperatingDay',
                    async: true,
                    success: function (response) {
                        if (response.success === true) {
                            toastr['success'](response.msg);
                            location.reload();
                        } else {
                            toastr['warning'](response.msg);
                        }
                    },
                    error: function () {
                        toastr['error']('Algo deu errado, tente novamente mais tarde ou contate-nos');
                    },
                    dataType: 'json'
                });
            },
            cancelar: function () { },
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
    $.each(events, function (i, val) {
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

// Função AJAX que Deleta o dia da tabela de NonOperatingDays
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
        success: function (response) {
            if (response.success === true) {
                toastr['success'](response.msg);
                location.reload();
            } else {
                toastr['warning'](response.msg);
            }
        },
        error: function () {
            toastr['error']('Algo deu errado, tente novamente mais tarde ou contate-nos');
        },
    });
}