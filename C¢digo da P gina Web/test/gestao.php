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
else if(count($_POST) != 0){
    $ficheiro_utilizadores = fopen('utilizadores.csv', 'r');
    $string_atualizada = '';
    while (($row = fgetcsv($ficheiro_utilizadores)) !== false) {
        if (isset($_POST[$row[0].'_opcoesNivel']) && $row[0] !=  ''){
            $row[2] = $_POST[$row[0].'_opcoesNivel'];
        }
        $string_atualizada .= (implode(',',$row)."\n");

    }
    fclose($ficheiro_utilizadores);

    $ficheiro_utilizadores = fopen('utilizadores.csv', 'w');
    fputs($ficheiro_utilizadores, $string_atualizada);
    fclose($ficheiro_utilizadores);
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
<script src="templates/gestao.js"></script>

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
                <li class="nav-item">
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
                </li>
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

    <a class="btn btn-primary mb-3" href="dashboard.php"><i class="bi bi-arrow-left"></i> Voltar</a>

    <form method="post">
        <div class="row">
            <div class="col">
                <div class="card mx-auto">
                    <div class="card-header text-center " style="padding: 15px;">
                        <div class="row">
                            <div class="col">
                                <h4>Nome</h4>
                            </div>
                            <div class="col">
                                <h4>Privilegios</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="utilizadores">
                        <!-- CONTEUDO GERADO EM JS -->
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

</body>
<script>
    //Url do site (MUDAR)
    const urlSite = 'http://192.168.1.83/test';

    // URL of the API endpoint
    const url = urlSite + "/utilizadores.csv";

    var selecionados = [];
    var novoHTML = '';
    var nivel_utilizador_pagina = "<?php echo $_SESSION['nivel']?>";
    var nome_utilizador_pagina = "<?php echo $_SESSION['username']?>"

    // Fetch data from the API
    fetch(url)
        .then(response => response.text())
        .then(csvText => {
            const rows = csvText.split('\n').map(row => row.split(','));
            for (const row of rows) {
                if (row[0] == ''){continue;}
                console.log(row[0]);
                var nome_utilizador_row = row[0];
                var nivel_utilizador_row = row[2];

                // Colocar a row com o nome correto
                var htmlRowUtilizador = template.row.replace(/\${NOME}/g, nome_utilizador_row);
                document.getElementById("utilizadores").innerHTML += htmlRowUtilizador;

                // Adicionar a o ID da checkbox selecionada a lista de selecionados
                selecionados.push(nome_utilizador_row+'_Nivel'+nivelToInt(nivel_utilizador_row));

                // Deixar a disabled os elementos com que o utilizador atual nao pode interagir
                var checkboxes = document.querySelectorAll('input[type="radio"][value="META_ADMIN"]');
                checkboxes.forEach(function (checkbox) {
                    checkbox.disabled = true;
                });
                if (nome_utilizador_row == nome_utilizador_pagina || nivelToInt(nivel_utilizador_pagina) <= nivelToInt(nivel_utilizador_row)){
                    checkboxes = document.querySelectorAll('input[type="radio"][name="'+nome_utilizador_row+'_opcoesNivel"]');

                    checkboxes.forEach(function (checkbox) {
                        checkbox.disabled = true;
                    });
                }
                else if(nivelToInt(nivel_utilizador_pagina) == 3){
                    checkboxes = document.querySelectorAll('input[type="radio"][value="ADMIN"]');
                    checkboxes.forEach(function (checkbox) {
                        checkbox.disabled = true;
                    });
                }
            }
            // Na lista de ids "selecionados" deixar cada um deles selecionado
            for (const id of selecionados) {
                document.getElementById(id).checked = true;
            }

        })
        .catch(error => {
            console.error(error);
        });

    function nivelToInt(nivel){
        var nivelInt;
        switch (nivel) {
            case "META_ADMIN":
                nivelInt = 4;
                break;
            case "ADMIN":
                nivelInt = 3;
                break;
            case "NORMAL":
                nivelInt = 2;
                break;
            case "RESTRITO":
                nivelInt = 1;
                break;
        }
        return nivelInt;
    }

</script>

</html>