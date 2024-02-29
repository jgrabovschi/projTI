var template = {
    divisoes : ' <div class="col">' +
        '<a class="card tiraDecoracao" href="divisao.php?divisao=${SECCAO_LINK}">' +
        '<div class="card-header text-center text-dark divisao">' +
        '<b>${SECCAO}</b>' +
        '</div>' +
        '<div class="card-body text-center">' +
        '<img class="w200" src="img/${SECCAO_LINK}.png" alt="divisao">' +
        '<!-- coloca os valores lidos dos ficheiros na parte em php numa tabela -->' +
        '<table class="table">' +
        '<thead>' +
        '<tr>' +
        '<th scope="col">Dispositivo IoT</th>' +
        '<th scope="col">Valor</th>' +
        '<th scope="col">Data de Atualização</th>' +
        '<th scope="col">Estado Alertas</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody>',
    dispositivos : '<tr>' +
        '<td>${NOME_DISPOSITIVO}</td>' +
        '<td>${VALOR_DISPOSITIVO}</td>' +
        '<td>${HORA_DISPOSITIVO}</td>' +
        '<td>${PILLL_DISPOSITIVO}</td>' +
        '</tr>',
    fim : '</tbody>' +
        '</table>' +
        '</div>' +
        '<br>' +
        '</a>' +
        '<br>' +
        '</div>'
};