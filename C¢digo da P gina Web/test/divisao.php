<?php

//  Inicia a sessão. Se entrar na pagina sem ter feito login,
// a pagina nao e mostrada e volta para a pagina de login depois de 5 segundos
session_start();
if (!(isset($_SESSION['username']) && isset($_SESSION['nivel']))) {
    header("refresh:5;url=index.php");
    die ("Acesso restrito.");
}

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo strtoupper($_GET['divisao']); ?></title>
    <link rel="icon" href="img/house-fill.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css"> <!-- importa o ficheiro de css -->

</head>

<body class="fundo">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="comportamentos/comportamentos.js"></script>
    <script src="templates/divisao.js"></script>

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

    <!-- CARDS QUE APARECEM EM CIMA -->
    <div class="container">
        <!-- botao para voltar -->
        <a class="btn btn-primary" href="dashboard.php"><i class="bi bi-arrow-left"></i> Voltar</a>
        <h1 class="text-center"><?php echo strtoupper($_GET['divisao']); ?></h1>

        <div class="row" id="row">
            <!-- Conteudo gerado em javascript -->
        </div>
    </div>

    <br>

    <!-- TABELA -->
    <div class="container">
        <div class="card">
            <div class="card-header">
                <b>Tabela de Sensores</b>
            </div>
            <div class="card-body">
            <table class="table"> 
                <thead>
                    <tr>
                        <th scope="col">Dispositivo IoT</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Data de Atualização</th>
                        <th scope="col">Estado Alertas</th>
                    </tr>
                </thead>
                <tbody id="tabela">
                    <!-- Conteudo gerado em javascript -->
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <br>
    <script>
        //Url do site (MUDAR)
        const urlSite = 'http://192.168.1.83/test';

        //Inicia a funcao do comportamento do switch
        comportamento_switch(urlSite);

        //Atualizacao dinamica da pagina
        const divisao_nome = "<?php echo $_GET['divisao']; ?>";
        const nivel_acesso = "<?php echo $_SESSION['nivel'];?>";
        async function atualizar_HTML(){
            // URL of the API endpoint
            const url = urlSite + "/api/files/estrutura.txt";

            // Fetch data from the API
            fetch(url)
                .then(response => response.text())
                .then(async data => {
                    // Strings que vao conter os codigos a serem injetados no html
                    var novoHTML_row = "";
                    var novoHTML_tabela = "";

                    //Transformar data num array em que array[i] = nome div; array[i+1] == dispositivos
                    var divisoes = data.split("\n");
                    var arrayDivisoesDispositivos = [];

                    for (const div of divisoes) {
                        var [divisao,dispositivos_string] = div.split(':');
                        var dispositivos_array = dispositivos_string.split(';');
                        arrayDivisoesDispositivos.push(divisao);
                        arrayDivisoesDispositivos.push(dispositivos_array);

                    }

                    //Encontrar a localizacao da divisao no arrayDivisoesDispositivos
                    var localizacao = arrayDivisoesDispositivos.findIndex((element) => element === divisao_nome);


                    //Criar array com os dispositivos da divisao da pagina
                    const dispositivos = arrayDivisoesDispositivos[localizacao + 1];

                    //Por cada dispositivo adiciona um card a row e uma row a tabela
                    for (const dispositivo of dispositivos) {
                        //Sacar e tratar das infos do dispositivo da API
                        const urls = [
                            urlSite + '/api/files/' + divisao_nome + '/' + dispositivo + '/nome.txt',
                            urlSite + '/api/files/' + divisao_nome + '/' + dispositivo + '/valor.txt',
                            urlSite + '/api/files/' + divisao_nome + '/' + dispositivo + '/hora.txt'
                        ];

                        const requests = urls.map(url => fetch(url));


                        await Promise.all(requests)
                            .then(responses => Promise.all(responses.map(response => response.text())))
                            .then(dataArray => {
                                //Tratamento das variaveis necessarias para o HTML
                                var nome = dataArray[0];
                                var valor = dataArray[1];
                                var hora = dataArray[2];

                                var comportamento = comportamentos(nome, valor, divisao_nome, false);

                                valor = comportamento.valor;
                                var pill = comportamento.pill;
                                var imagem = comportamento.imagem;

                                //Colocacao das variaveis no HTML da row a ser injetado
                                var htmlRow = "";

                                var htmlInicioRow = template.inicio_row.replace(/\${SECCAO}/g, divisao_nome);
                                htmlInicioRow = htmlInicioRow.replace(/\${NOME}/g, nome);
                                htmlInicioRow = htmlInicioRow.replace(/\${VALOR}/g, valor);
                                htmlInicioRow = htmlInicioRow.replace(/\${IMAGEM}/g, imagem);
                                htmlInicioRow = htmlInicioRow.replace(/\${HORA}/g, hora);

                                htmlRow += htmlInicioRow;

                                //Caso o utilizador tenha privilegios para ver o historico
                                if (nivel_acesso == "ADMIN" || nivel_acesso == "META_ADMIN"){
                                    var htmlHistorico = template.historico.replace(/\${NOME}/g, nome);
                                    htmlHistorico = htmlHistorico.replace(/\${SECCAO}/g, divisao_nome);

                                    htmlRow += htmlHistorico;
                                }

                                htmlRow += template.fim_row;
                                novoHTML_row += htmlRow;

                                //Colocacao das variaveis no HTML da tabela a ser injetado
                                var htmlTabela = template.tabela.replace(/\${NOME}/g, nome);
                                htmlTabela = htmlTabela.replace(/\${VALOR}/g, valor);
                                htmlTabela = htmlTabela.replace(/\${HORA}/g, hora);
                                htmlTabela = htmlTabela.replace(/\${PILL}/g, pill);
                                novoHTML_tabela += htmlTabela;
                            })
                            .catch(error => {
                                console.log('Erro:', error);
                            });
                    }

                    // Injecao do codigo HTML atualizado na pagina
                    document.getElementById("row").innerHTML = novoHTML_row;
                    document.getElementById("tabela").innerHTML = novoHTML_tabela;
                })
                .catch(error => {
                    console.error('Erro:', error);
                })
            setTimeout(atualizar_HTML,5000);
        }
        atualizar_HTML();
    </script>
</body>

</html>