<?php

//  Inicia a sessão. Se entrar na pagina sem ter feito login,
// a pagina nao e mostrada e volta para a pagina de login depois de 5 segundos
session_start();
if (!(isset($_SESSION['username']) && isset($_SESSION['nivel']))) {
    header("refresh:5;url=index.php");
    die ("Acesso restrito.");
}

?>

<!DOCTYPE html>

<html lang="pt">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About</title>
    <link rel="icon" href="img/house-fill.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">

</head>

<body class="fundo">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

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
                        <a href="historico.php" class="nav-link">Historico</a>
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

    <!-- CARD -->
    <div class="container"> 
        <div class="row">
            <div class="col">
                <div class="card mx-auto">
                    <div class="card-header text-center " style="padding: 15px;">
                        <h2><b>Projeto Smart House</b></h2>
                    </div>
                    <div class="card-body">
                        <div class="text-center" style="padding: 15px;">
                            <p>2221469 - Jorge Grabovschi</p> 
                            <p>2221970 - Eduardo Gonçalves</p>                           
                        </div>
                    </div>
                    <div class="card-footer text-center" style="padding: 15px;">
                        Trabalho desenvolvido no âmbito da unidade curricular de Tecnologias da Internet, do curso de Engenharia Informática. 
                    </div>
                </div>
            </div>
        </div>

    </div>
    <br>
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <!-- botao para voltar -->
                <a class="btn btn-primary" href="dashboard.php"><i class="bi bi-arrow-left"></i> Voltar</a>
            </div>
        </div>
    </div>
    
</body>

</html>