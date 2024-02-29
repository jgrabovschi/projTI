<?php
    session_start();

    //flags para quando o username e/ou pass estiverem erradas as borders das forms ficarem vermelhas
    $flag_username = 0;
    $flag_password = 0;

    //verifica se a password e username estão corretas quando forem sido submetidas
    if (isset($_POST['password']) && isset($_POST['username'])){
        $ficheiro_utilizadores = fopen('utilizadores.csv', 'r');
        while (($row = fgetcsv($ficheiro_utilizadores)) !== false) {
            $flag_username = 0;
            $flag_password = 0;
            if ($_POST['username'] != $row[0]){
                $flag_username = 1;
                continue;
            }

            if (!password_verify($_POST['password'], $row[1])){
                $flag_password = 1;
                continue;
            }


            echo 'password correta';
            fclose($ficheiro_utilizadores);
            $_SESSION["username"]=$_POST['username'];
            $_SESSION["nivel"]=$row[2];
            header("refresh:0;url=dashboard.php");
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

                <!-- BOTAO PARA SUBMETER -->
                <div class="text-center">
                    <button type="submit" class="btn btn-light">Submeter</button>
                </div>
                <!-- Link para a pagina de registo -->
                <div class="text-center">
                    <a href="registo.php" class="text-light">Registar</a>
                </div>

                <br>
                <br>

            </form>
        </div>
    </div>  
</body>

</html>
