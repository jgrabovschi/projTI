var template = {
    row : '<div class="row text-center">' +
        '                            <div class="col">' +
        '                                ${NOME}' +
        '                            </div>' +
        '                            <div class="col">' +
        '                                <div class="form-check form-check-inline">' +
        '                                    <input class="form-check-input" type="radio" name="${NOME}_opcoesNivel" id="${NOME}_Nivel4" value="META_ADMIN">' +
        '                                    <label class="form-check-label" for="inlineRadio1">Meta Administrador</label>' +
        '                                </div>' +
        '                                <div class="form-check form-check-inline">' +
        '                                    <input class="form-check-input" type="radio" name="${NOME}_opcoesNivel" id="${NOME}_Nivel3" value="ADMIN">' +
        '                                    <label class="form-check-label" for="inlineRadio2">Administrador</label>' +
        '                                </div>' +
        '                                <div class="form-check form-check-inline">' +
        '                                    <input class="form-check-input" type="radio" name="${NOME}_opcoesNivel" id="${NOME}_Nivel2" value="NORMAL">' +
        '                                    <label class="form-check-label" for="inlineRadio3">Normal</label>' +
        '                                </div>' +
        '                                <div class="form-check form-check-inline">' +
        '                                    <input class="form-check-input" type="radio" name="${NOME}_opcoesNivel" id="${NOME}_Nivel1" value="RESTRITO">' +
        '                                    <label class="form-check-label" for="inlineRadio3">Restrito</label>' +
        '                                </div>' +
        '                            </div>' +
        '                        </div>'
}