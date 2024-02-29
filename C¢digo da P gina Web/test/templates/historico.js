var template = {
    divisoes: '<div class="row justify-content-center">' +
        '<h1 class="text-center">${SECCAO}</h1>',
    dispositivos: '<div class="col-md-4">' +
        '<div class="card mx-auto w300 text-center">' +
        '<div class="card-header quarto">' +
        '<b>${NOME_DISPOSITIVO}</b>' +
        '</div>' +
        '<div class="card-body scrollCard">' +
        '<table class="table">' +
        '<thead>' +
        '<tr>' +
        '<th>Data</th>' +
        '<th>Valor</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody>',
    linha: '<tr>' +
        '<td>${HORA_DISPOSITIVO}</td>' +
        '<td>${VALOR_DISPOSITIVO}</td>' +
        '</tr>',
    fim_dispositivo: '</tbody></table></div></div><br></div>',
    fim_divisao: '</div> <br>'
}