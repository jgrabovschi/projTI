<?php

//  Inicia a sessão. Se entrar na pagina sem ter feito login,
// a pagina nao e mostrada e volta para a pagina de login depois de 5 segundos
session_start();
if (!(isset($_SESSION['username']) && isset($_SESSION['nivel']))) {
    header("refresh:5;url=index.php");
    die ("Acesso restrito.");
}
else if ($_SESSION['nivel'] == 'RESTRITO' || $_SESSION['nivel'] == 'NORMAL'){
    header("refresh:5;url=dashboard.php");
    die ("Acesso restrito.");
}

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Histórico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css"> <!-- importa o ficheiro de css -->

</head>

<body class="fundo">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="comportamentos/comportamentos.js"></script>
    <script src="templates/historico.js"></script>

    <!-- BARRA DE NAVEGACAO -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">

            <!-- BOTAO QUE APARECE QUANDO A NAVBAR COLAPSA (NOS ECRAS MAIS PEQUENOS) -->
            <!-- PARA MOSTRAR AS OPCOES QUE SE ENCONTRAM NO DIV COM ID "navbarSupportedContent" -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="dashboard.php">Olá <?php echo $_SESSION["username"] ?> bem-vindo ao Dashboard</a>
                    </li>
                    <?php
                        if ($_SESSION["nivel"] == "ADMIN" || $_SESSION["nivel"] == "META_ADMIN" ){
                            echo '
                            <li class="nav-item">
                                <a href="historico.php" class="nav-link">Historico</a>
                            </li>
                            <li class="nav-item">
                                <a href="gestao.php" class="nav-link">Gestao</a>
                            </li>';

                        }
                        ?>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>

                <!-- BOTAO DE LOGOUT -->
                <a class="btn btn-outline-danger" href="logout.php">Logout</a>

            </div>
        </div>
    </nav>

    <!-- IMAGEM DE FUNDO REDONDO E AZUL QUE ESTA NO TOPO COM A CASA DE BRANCO -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="home bg-primary bg-gradient rounded-circle">
                <a href="dashboard.php" class="row text-center navbar-expand-sm justify-content-center">
                    <i class="bi bi-house text-light" style="font-size: 65px"></i>
                </a>
            </div>
        </div>
    </div>

    <br>

    <div class="container">

        <a class="btn btn-primary" href="dashboard.php"><i class="bi bi-arrow-left"></i> Voltar</a>

        <div id="historico"></div>

    </div>

<script>
    async function atualizar_HTML(){
        const urlSite = 'http://192.168.1.83/test';
        // URL of the API endpoint
        const url = urlSite + "/api/files/estrutura.txt";

        // Fetch data from the API
        fetch(url)
            .then(response => response.text())
            .then(async data => {
                // String que vai conter o codigo a ser injetado no html
                var novoHTML = "";


                var divisoes = data.split("\n");

                //Por cada divisao constroi uma nova row
                for (const divisao of divisoes) {
                    var divisao_array = divisao.split(':');
                    var divisao_nome = divisao_array[0];

                    var htmlDivisao = template.divisoes.replace(/\${SECCAO}/g, divisao_nome.toUpperCase());
                    novoHTML += htmlDivisao;

                    var dispositivos = divisao_array[1].split(';');

                    //Por cada dispositivo adiciona um cartao
                    for (const dispositivo of dispositivos) {
                        //Sacar o log da API
                        const urls = [
                            urlSite + '/api/files/' + divisao_nome + '/' + dispositivo + '/nome.txt',
                            urlSite + '/api/files/' + divisao_nome + '/' + dispositivo + '/log.txt'
                        ];

                        const requests = urls.map(url => fetch(url));


                        await Promise.all(requests)
                            .then(responses => Promise.all(responses.map(response => response.text())))
                            .then(dataArray => {
                                //Tratamento das variaveis necessarias para o HTML
                                var nomeDispositivo = dataArray[0];
                                var logDispositivo = dataArray[1].split('\n');

                                //Colocacao das variaveis no HTML a ser injetado
                                var htmlDispositivo = template.dispositivos.replace(/\${NOME_DISPOSITIVO}/g, nomeDispositivo);
                                novoHTML += htmlDispositivo;
                                //var comportamento = comportamentos(nomeDispositivo, valorDispositivo);

                                //Por cada linha no log adiciona uma linha ao historico do dispositivo
                                for (const linha of logDispositivo){
                                    var linha_array = linha.split(';');
                                    var horaDispositivo = linha_array[0];
                                    var valorDispositivo = linha_array[1];

                                    if (valorDispositivo === undefined) { break; }

                                    var comportamento = comportamentos(nomeDispositivo, valorDispositivo, "", true);
                                    valorDispositivo = comportamento.valor;

                                    var htmlLinha = template.linha.replace(/\${HORA_DISPOSITIVO}/g, horaDispositivo);
                                    htmlLinha = htmlLinha.replace(/\${VALOR_DISPOSITIVO}/g, valorDispositivo);
                                    novoHTML += htmlLinha;
                                }
                            })
                            .catch(error => {
                                console.log('Erro:', error);
                            });
                        novoHTML += template.fim_dispositivo;
                    }
                    novoHTML += template.fim_divisao;

                }


                // Injecao do codigo HTML atualizado na pagina
                document.getElementById("historico").innerHTML = novoHTML;
            })
            .catch(error => {
                console.error('Erro:', error);
            })
        setTimeout(atualizar_HTML,30000);
    }
    atualizar_HTML();
</script>
</body>

</html>