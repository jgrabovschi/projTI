<?php
// Java Web Token
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
    <title>DASHBOARD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css"> <!-- importa o ficheiro de css -->

</head>

<body class="fundo">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="comportamentos/comportamentos.js"></script>
    <script src="templates/dashboard.js"></script>

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
    
    <!-- INFORMACAO DE AJUDA -->
    <div class="container">
        <div class="row col justify-content-center">
            <div class="card w-auto border-primary">
                <div class="ajuda"><i class="bi bi-info-circle"></i>
                    
                     <?php
                        if ($_SESSION["nivel"] != "RESTRITO"){
                            echo 'Carregue num dos cards para aceder à dashboard da divisão.';
                        }
                        else{
                            echo 'Contacte um administrador para ter acesso aos conteudos da Smart Home.';
                        }

                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <br>


    <!-- CONTAINER COM OS CARDS DA SALA E DO QUARTO -->
    <?php
    if ($_SESSION["nivel"] != "RESTRITO"){
        echo '
        <div class="container">
            <div class="row" id="container_divisoes">
            </div>
        </div>';
    }
    ?>

<script>
    //Url do site (MUDAR)
    const urlSite = 'http://192.168.1.83/test';

    //Inicia a funcao do comportamento do switch
    comportamento_switch(urlSite);

    //Atualizacao dinamica da pagina
    async function atualizar_HTML(){
        // URL of the API endpoint
        const url = urlSite + "/api/files/estrutura.txt";

        // Fetch data from the API
        fetch(url)
            .then(response => response.text())
            .then(async data => {
                // String que vai conter o codigo a ser injetado no html
                var novoHTML = "";


                var divisoes = data.split("\n");

                //Por cada divisao constroi um novo card
                for (const divisao of divisoes) {
                    var divisao_array = divisao.split(':');
                    var divisao_nome = divisao_array[0];

                    var htmlDivisao = template.divisoes.replace(/\${SECCAO}/g, divisao_nome.toUpperCase());
                    htmlDivisao = htmlDivisao.replace(/\${SECCAO_LINK}/g, divisao_nome);

                    novoHTML += htmlDivisao;


                    var dispositivos = divisao_array[1].split(';');

                    //Por cada dispositivo adiciona rows com a info do dispositivo ao cartao
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
                                var nomeDispositivo = dataArray[0];
                                var valorDispositivo = dataArray[1];
                                var horaDispositivo = dataArray[2];

                                var comportamento = comportamentos(nomeDispositivo, valorDispositivo, divisao_nome);

                                valorDispositivo = comportamento.valor;
                                var pillDispositivo = comportamento.pill;

                                //Colocacao das variaveis no HTML a ser injetado
                                var htmlDispositivos = template.dispositivos.replace(/\${NOME_DISPOSITIVO}/g, nomeDispositivo);
                                htmlDispositivos = htmlDispositivos.replace(/\${VALOR_DISPOSITIVO}/g, valorDispositivo);
                                htmlDispositivos = htmlDispositivos.replace(/\${HORA_DISPOSITIVO}/g, horaDispositivo);
                                htmlDispositivos = htmlDispositivos.replace(/\${PILLL_DISPOSITIVO}/g, pillDispositivo);

                                novoHTML += htmlDispositivos;
                            })
                            .catch(error => {
                                console.log('Erro:', error);
                            });
                    }
                    novoHTML += template.fim;

                }

                // Injecao do codigo HTML atualizado na pagina
                document.getElementById("container_divisoes").innerHTML = novoHTML;
            })
            .catch(error => {
                console.error('Erro:', error);
            })
        setTimeout(atualizar_HTML,500);
    }
    atualizar_HTML();

</script>

</body>

</html>
