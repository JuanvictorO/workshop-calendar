document.addEventListener("DOMContentLoaded", function () {
    let data = new Date();
    let data2 = new Date(data.valueOf() - data.getTimezoneOffset() * 60000);
    var dataBase = data2.toISOString().replace(/\.\d{3}Z$/, "");
    dataBase = dataBase.split("T");
    const dataAtual = dataBase[0];

    var calendarEl = document.getElementById("calendar");

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: "pt-br",
        plugins: ["interaction", "dayGrid"],
        //defaultDate: '2019-04-12',
        editable: true,
        eventLimit: true,
        hiddenDays: [7],
        /* dayRender: function (date, cell) {
            console.log(date);
            var dataDia = date.date.toISOString().replace(/\.\d{3}Z$/, "");
            dataDia = dataDia.split("T");
            console.log(dataDia[0] + " < " + dataAtual);
            console.log(cell);
            if (dataDia[0] < dataAtual) {
                $(cell).addClass("disabled");
            }
        },*/
        /*dayClick: function (date, jsEvent, view) {
            if ($(jsEvent.target).hasClass("disabled")) {
                return false;
            }
        },*/
        //events: 'list_eventos.php',
        extraParams: function () {
            return {
                cachebuster: new Date().valueOf(),
            };
        },
        eventClick: function (info) {
            info.jsEvent.preventDefault(); // don't let the browser navigate

            $("#visualizar #id").text(info.event.id);
            $("#visualizar #title").text(info.event.title);
            $("#visualizar #start").text(info.event.start.toLocaleString());
            $("#visualizar #end").text(info.event.end.toLocaleString());
            $("#visualizar").modal("show");
        },
        selectable: true,
        select: function (info) {
            //alert('Início do evento: ' + info.start.toLocaleString());
            var dataDia = info.start.toISOString().replace(/\.\d{3}Z$/, "");
            dataDia = dataDia.split("T");
            if (dataDia[0] < dataAtual) {
                toastr["warning"]("Você não pode marcar a consulta nesse dia!");
            } else {
                $("#cadastrar #start").val(info.start.toLocaleString());
                $("#cadastrar #end").val(info.end.toLocaleString());
                $("#cadastrar").modal("show");
            }
        },
    });

    calendar.render();
});

//Mascara para o campo data e hora
function DataHora(evento, objeto) {
    var keypress = window.event ? event.keyCode : evento.which;
    campo = eval(objeto);
    if (campo.value == "00/00/0000 00:00:00") {
        campo.value = "";
    }

    caracteres = "0123456789";
    separacao1 = "/";
    separacao2 = " ";
    separacao3 = ":";
    conjunto1 = 2;
    conjunto2 = 5;
    conjunto3 = 10;
    conjunto4 = 13;
    conjunto5 = 16;
    if (
        caracteres.search(String.fromCharCode(keypress)) != -1 &&
        campo.value.length < 19
    ) {
        if (campo.value.length == conjunto1)
            campo.value = campo.value + separacao1;
        else if (campo.value.length == conjunto2)
            campo.value = campo.value + separacao1;
        else if (campo.value.length == conjunto3)
            campo.value = campo.value + separacao2;
        else if (campo.value.length == conjunto4)
            campo.value = campo.value + separacao3;
        else if (campo.value.length == conjunto5)
            campo.value = campo.value + separacao3;
    } else {
        event.returnValue = false;
    }
}