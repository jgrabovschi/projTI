<?php
session_start();

//flag para quando o username e/ou pass estiverem erradas as borders das forms ficarem vermelhas
$flag_username = 0;
$flag_password = 0;

//verifica se a password e username estão corretas quando forem sido submetidas
if (isset($_POST['password']) && isset($_POST['username']) && isset($_POST['password_confirm'])){
    $ficheiro_utilizadores = fopen('utilizadores.csv', 'r+');
    while (($row = fgetcsv($ficheiro_utilizadores)) !== false) {
        $flag_username = 0;
        $flag_password = 0;
        if ($_POST['username'] == $row[0]){
            $flag_username = 1;
            break;
        }
        if ($_POST['password'] != $_POST['password_confirm']) {
            $flag_password = 1;
            break;
        }
    }
    // Se tudo estiver e ordem adiciona o utilizador aos utilizadores.csv
    if (!($flag_username || $flag_password)){
        $hash_da_passe = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $nivel_de_acesso = 'RESTRITO';

        fseek($ficheiro_utilizadores, 0, SEEK_END);
        $novo_utilizador = [$_POST['username'], $hash_da_passe, $nivel_de_acesso];
        fputcsv($ficheiro_utilizadores, $novo_utilizador);
        fclose($ficheiro_utilizadores);
        header("refresh:0;url=index.php");
    }

}

?>


<!doctype html>

<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="img/house-fill.svg" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="style.css" rel="stylesheet">
</head>

<body class="fundo">
<br>
<!-- INFORMACAO DE AJUDA -->
<div class="container">
    <div class="row col justify-content-center">
        <div class="card w-auto border-primary">
            <div class="ajuda"><i class="bi bi-info-circle"></i> O seu acesso sera restrito ate um Administrador o alterar </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row justify-content-center">
        <form class="TIform bg-primary bg-gradient rounded-5 border border-5" method="post">
            <!-- ICONE -->
            <a href="index.php" class="row text-center">
                <i class="bi bi-house text-light" style="font-size: 150px"></i><b class="text-light">Smart Home</b>
            </a>

            <br>
            <br>

            <!-- FORMULARIO DE LOGIN -->
            <!-- NA PARTE DE PHP A BORDER MUDA DE COR (VERMELHO) QUANDO A FLAG ESTIVER A 1, -->
            <!-- OU SEJA QUANDO A PASSWORD E/ OU USERNAME ESTIVEREM ERRADAS -->
            <div class="mb-3">
                <input name="username" type="text" class="form-control <?php if($flag_username==1){echo'border-danger';}?>" placeholder="Username" required>
            </div>

            <div class="mb-3">
                <input name="password" type="password" class="form-control <?php if($flag_password==1){echo'border-danger';}?>" placeholder="Password" required>
            </div>

            <div class="mb-3">
                <input name="password_confirm" type="password" class="form-control <?php if($flag_password==1){echo'border-danger';}?>" placeholder="Confirmar Password" required>
            </div>

            <!-- BOTAO PARA SUBMETER -->
            <div class="text-center">
                <button type="submit" class="btn btn-light">Submeter</button>
            </div>

            <br>
            <br>

        </form>
    </div>
</div>
</body>

</html>
