var template = {
    dispositivo: '<a class="btn btn-primary" href="divisao.php?divisao=${SECCAO}">' +
        '<i class="bi bi-arrow-left"></i> Voltar</a>' +
        '<div class="row justify-content-center">' +
        '<div class="col-md-4">' +
        '<div class="card mx-auto text-center">' +
        '<div class="card-header ${SECCAO}">' +
        '<b>${NOME_DISPOSITIVO} - ${SECCAO_MAIUSCULA}</b></div>' +
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
    fim: '</tbody></table></div></div><br></div></div>'
}