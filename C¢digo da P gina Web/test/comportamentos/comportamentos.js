function Comportamento(imagem, pill, valor){
    this.imagem = imagem;
    this.pill = pill;
    this.valor = valor;
}

//Exemplos de inputs
//dispositivo: humidade; luz; temperatura
//valor: 10; desligada; 30
function comportamentos(dispositivo, valor, divisao, historico){
    var comportamento = new Comportamento;

    switch (dispositivo.toLowerCase()) {
        case "chamas":
            chamas(valor, comportamento);
            break;
        case "humidade":
            humidade(valor, comportamento);
            break;
        case "imagem":
            imagem(valor, comportamento);
            break;
        case "luz":
            luz(valor, comportamento, divisao, historico);
            break;
        case "led":
            led(valor, comportamento);
            break;
        case "movimento":
            movimento(valor, comportamento);
            break;
        case "porta":
            porta(valor, comportamento, divisao, historico);
            break;
        case "temperatura":
            temperatura(valor, comportamento);
            break;
        case "luz presente":
            luzPresente(valor, comportamento);
            break;
        case "ventoinha":
            ventoinha(valor, comportamento);
            break;
        case "candeeiro":
            candeeiro(valor, comportamento);
            break;
        default:
            console.log("Dispositivo invalido");

    }

    return comportamento;
}

function candeeiro(valor, comportamento) {
        //Verificacoes para a imagem

        comportamento.imagem = '<img src="img/ceiling-lamp.png" alt="lamp" style="height: 128px;" >';
    
        //Verificacoes para a pill
        if (valor.toLowerCase() == 'ligado') {
            comportamento.pill = '<span class="badge rounded-pill text-bg-primary">Ativo</span>';
        }
        else {
            comportamento.pill = '<span class="badge rounded-pill text-bg-danger">Não Ativo</span>';
        }
    
        //valor 
        comportamento.valor = valor.toUpperCase();
}

function led(valor, comportamento) {
    //Verificacoes para a imagem
    if (valor.toLowerCase() == 'ligado') {
        comportamento.imagem = '<img src="img/light-on.png" alt="high" >';
    }
    else {
        comportamento.imagem = '<img src="img/light-off.png" alt="low" >';
    }

    //Verificacoes para a pill
    if (valor.toLowerCase() == 'ligado') {
        comportamento.pill = '<span class="badge rounded-pill text-bg-primary">Ativo</span>';
    }
    else {
        comportamento.pill = '<span class="badge rounded-pill text-bg-danger">Não Ativo</span>';
    }

    //valor 
    comportamento.valor = valor.toUpperCase();
}

function ventoinha(valor, comportamento) {
        //imagem
        comportamento.imagem = '<img src="img/ceiling-fan.png" alt="fan" style="height:128px;" >';
        
    
        //Verificacoes para a pill
        if (valor.toLowerCase() == 'ligada') {
            comportamento.pill = '<span class="badge rounded-pill text-bg-primary">Ativo</span>';
        }
        else {
            comportamento.pill = '<span class="badge rounded-pill text-bg-danger">Não Ativo</span>';
        }
    
        // Valor
        comportamento.valor = valor.toUpperCase();
}

function luzPresente(valor, comportamento) {
    //Verificacoes para a imagem
    if (valor.toLowerCase() == "presente"){
        comportamento.imagem = '<img src="img/brightness-high.png" alt="presente" style="height:128px;">'
    }
    else {
        comportamento.imagem = '<img src="img/brightness-low.png" alt="nao presente" style="height:128px;">'
    }
    //Verificacoes para a pill
    if (valor.toLowerCase() == "presente"){
        comportamento.pill = '<span class="badge rounded-pill text-bg-danger">Presente</span>'
    }
    else {
        comportamento.pill = '<span class="badge rounded-pill text-bg-info">Nao Presente</span>'
    }
    //Valor
    comportamento.valor = valor.toUpperCase();
}

function chamas(valor, comportamento){
    //Verificacoes para a imagem
    if (valor.toLowerCase() == "presentes"){
        comportamento.imagem = '<img src="img/chamas-on.png" alt="presentes" style="height:128px;">'
    }
    else {
        comportamento.imagem = '<img src="img/chamas-off.png" alt="nao presentes" style="height:128px;">'
    }
    //Verificacoes para a pill
    if (valor.toLowerCase() == "presentes"){
        comportamento.pill = '<span class="badge rounded-pill text-bg-danger">Presentes</span>'
    }
    else {
        comportamento.pill = '<span class="badge rounded-pill text-bg-info">Nao Presentes</span>'
    }
    //Valor
    comportamento.valor = valor.toUpperCase();
}

function humidade(valor, comportamento){
    //Verificacoes para a imagem
    if (valor > 60){
        comportamento.imagem = '<img src="img/humidity-high.png" alt="high">';
    }
    else {
        comportamento.imagem = '<img src="img/humidity-low.png" alt="low">';
    }

    //Verificacoes para a pill
    if (valor >= 60) {
        comportamento.pill = '<span class="badge rounded-pill text-bg-danger">Elevada</span>';
    }
    else if (valor >= 15) {
        comportamento.pill = '<span class="badge rounded-pill text-bg-success">Normal</span>';
    }
    else {
        comportamento.pill = '<span class="badge rounded-pill text-bg-info">Baixa</span>';
    }

    //Valor
    comportamento.valor = valor + '%';
}

function imagem(valor, comportamento){
    // imagem
    comportamento.imagem = "<img src='api/images/webcam.jpg?id=" + (Date.now()/1000) + "' alt='imagem' style='width:300px'>";
    
    // pill
    comportamento.pill = '<span class="badge rounded-pill text-bg-primary">Ativo</span>';
    
    //Valor
    comportamento.valor = "";

}

function luz(valor, comportamento, divisao, historico){
    //Verificacoes para a imagem
    if (valor.toLowerCase() == 'ligada') {
        comportamento.imagem = '<img src="img/light-on.png" alt="high" >';
    }
    else {
        comportamento.imagem = '<img src="img/light-off.png" alt="low" >';
    }

    //Verificacoes para a pill
    if (valor.toLowerCase() == 'ligada') {
        comportamento.pill = '<span class="badge rounded-pill text-bg-primary">Ativo</span>';
    }
    else {
        comportamento.pill = '<span class="badge rounded-pill text-bg-danger">Não Ativo</span>';
    }
    // Valor
    comportamento.valor = valor.toUpperCase();
    if (!historico) {
        if (valor.toLowerCase() == 'ligada' ){
            comportamento.valor = '<div class="form-check form-switch">' +
                '        <input class="form-check-input" type="checkbox" id="luz_' + divisao + '" checked>' +
                '        <label class="form-check-label" for="toggleSwitch" class="switch-text">' +
                '            <span class="switch-text">ON</span>' +
                '        </label>' +
                '    </div>';
        }
        else {
            comportamento.valor = '<div class="form-check form-switch">' +
                '        <input class="form-check-input" type="checkbox" id="luz_' + divisao + '">' +
                '        <label class="form-check-label" for="toggleSwitch">' +
                '            <span class="switch-text">OFF</span>' +
                '        </label>' +
                '    </div>';
        }
    }


}

function movimento(valor, comportamento){
    // imagem
    comportamento.imagem = '<img src="img/move.png" alt="move" style="height:128px;">';
    
    //Verificacoes para a pill
    comportamento.pill = '<span class="badge rounded-pill text-bg-primary">Ativo</span>';
    
    //Valor
    comportamento.valor = valor;

}

function porta(valor, comportamento, divisao, historico){
    //Verificacoes para a imagem
    if (valor.toLowerCase() == 'aberta') {
        comportamento.imagem = '<img src="img/door-open.svg" alt="open" style="width: 128px;">';
    }
    else {
        comportamento.imagem = '<img src="img/door-closed.svg" alt="closed" style="width: 128px;">';
    }

    //Verificacoes para a pill
    comportamento.pill = '<span class="badge rounded-pill text-bg-primary">Ativo</span>';

    //Valor
    comportamento.valor = valor.toUpperCase();
    if (historico == true){return;}

    if (valor.toLowerCase() == 'aberta'){
        comportamento.valor = '<div class="form-check form-switch">' +
            '        <input class="form-check-input" type="checkbox" id="porta_' + divisao + '" checked>' +
            '        <label class="form-check-label" for="toggleSwitch" class="switch-text">' +
            '            <span class="switch-text">ABERTA</span>' +
            '        </label>' +
            '    </div>';
    }
    else {
        comportamento.valor = '<div class="form-check form-switch">' +
            '        <input class="form-check-input" type="checkbox" id="porta_' + divisao + '">' +
            '        <label class="form-check-label" for="toggleSwitch">' +
            '            <span class="switch-text">FECHADA</span>' +
            '        </label>' +
            '    </div>';
        }
    
        

}

function temperatura(valor, comportamento){
    //Verificacoes para a imagem
    if (valor > 25) {
        comportamento.imagem = '<img src="img/temperature-high.png" alt="high" >';
    }
    else {
        comportamento.imagem = '<img src="img/temperature-low.png" alt="low" >';
    }

    //Verificacoes para a pill
    if (valor >= 25) {
        comportamento.pill = '<span class="badge rounded-pill text-bg-danger">Elevada</span>';
    }
    else if (valor >= 15) {
        comportamento.pill = '<span class="badge rounded-pill text-bg-success">Normal</span>';
    }
    else {
        comportamento.pill = '<span class="badge rounded-pill text-bg-info">Baixa</span>';
    }

    //Valor
    comportamento.valor = valor + ' º';

}

function comportamento_switch(urlSite){
    document.addEventListener("DOMContentLoaded", function() {
        document.addEventListener("change", function(event) {
            var target = event.target;
            if (target.classList.contains("form-check-input")) {
                //Perceber em que dispositivo ocorreu a alteracao e colocacao dos dados adequados nas variaveis
                var dispositivo = target.id.split('_')[0];
                var divisao = target.id.split('_')[1];
                const atributos_switch = {
                    valor : {
                        on : '',
                        off: ''
                    },
                    conteudo_texto : {
                        on : '',
                        off: ''
                    }
                };
                if (dispositivo == 'luz'){
                    atributos_switch.valor.on = 'Ligada';
                    atributos_switch.valor.off = 'Desligada';
                    atributos_switch.conteudo_texto.on = 'On';
                    atributos_switch.conteudo_texto.on = 'Off';
                }
                else if (dispositivo == 'porta'){
                    atributos_switch.valor.on = 'Aberta';
                    atributos_switch.valor.off = 'Fechada';
                    atributos_switch.conteudo_texto.on = 'Aberta';
                    atributos_switch.conteudo_texto.on = 'Fechada';
                }

                //Criacao das variaveis para o POST
                const urlAPI = urlSite + '/api/api.php';
                var hora = new Date();
                hora = hora.toISOString().split('T')[0] + ' ' + hora.toISOString().split('T')[1].split('.')[0];
                const dados = new FormData();
                dados.append('divisao', divisao);
                dados.append('nome', dispositivo);
                dados.append('hora', hora);


                //Mudar o texto ao lado do switch e o inserir o valor correto em dados
                var switchText = target.nextElementSibling.querySelector(".switch-text");
                if (target.checked) {
                    switchText.textContent = atributos_switch.conteudo_texto.on.toUpperCase();
                    dados.append('valor', atributos_switch.valor.on);
                } else {
                    switchText.textContent = atributos_switch.conteudo_texto.off.toUpperCase();
                    dados.append('valor', atributos_switch.valor.off);
                }

                //Efetuar as alteracoes correspondentes na API
                fetch(urlAPI, {
                    method: 'POST',
                    body: dados
                })
                    .then(response => response)
                    .then(responseData => {
                        // Handle the response data
                        console.log(responseData);
                    })
                    .catch(error => {
                        // Handle any errors
                        console.error(error);
                    });

            }
        });
    });
}