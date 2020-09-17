const baseUrl = $('#baseUrl').val();

toastr.options = {
    "closeButton": true,
    "preventDuplicates": true,
    "progressBar": true,
}

let data = new Date();
let data2 = new Date(data.valueOf() - data.getTimezoneOffset() * 60000);
var dataBase = data2.toISOString().replace(/\.\d{3}Z$/, "");
dataBase = dataBase.split("T");
const dataAtual = dataBase[0];