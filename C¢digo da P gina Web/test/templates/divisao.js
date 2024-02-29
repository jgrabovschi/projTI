var template = {
    inicio_row : '<div class="col">' +
        '<div class="card">' +
        '<div class="card-header text-center ${SECCAO}" style="padding: 15px;">' +
        '<div class="row align-items-center justify-content-center no-gutters">'+
        '<div class="col center">${NOME}: </div>' +
        '<div class="col center">${VALOR}</div>' +
        '</div></div>' +
        '<div class="card-body text-center">' +
        '${IMAGEM}' +
        '</div>' +
        '<div class="card-footer text-center" style="padding: 15px;">' +
        '<b>Atualização: </b>${HORA}',
    historico : '<a href="historicoSensor.php?nome=${NOME}&divisao=${SECCAO}">Histórico</a>',
    fim_row : '</div></div><br></div>',
    tabela : '<tr>' +
        '<td>${NOME}</td>' +
        '<td>${VALOR}</td>' +
        '<td>${HORA}</td>' +
        '<td>${PILL}</td>' +
        '</tr>'
}