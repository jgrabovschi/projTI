<?php

    /*
        PARAMETROS PARA O METODO GET:
            - NOME (SENSOR OU ATUADOR)
            - DIVISAO (SALA OU QUARTO)
        
        PARAMETROS PARA O METODO POST:
            - NOME (SENSOR OU ATUADOR)
            - DIVISAO (SALA OU QUARTO)
            - HORA (DATA/HORA)
            - VALOR
    */

    // header('Content-Type: text/html; charset=utf-8');
    // http_response_code(500);

    if($_SERVER['REQUEST_METHOD']=="GET"){
        // PEDIDO GET
        if(isset($_GET['nome']) and isset($_GET['divisao']) and file_exists("files/".$_GET['divisao']."/".$_GET['nome'])){
            //VAI BUSCAR AOS FICHEIROS A INFORMACAO PEDIDA SE OS PARAMETROS FOREM VALIDOS
            echo file_get_contents("files/".$_GET['divisao']."/".$_GET['nome']."/valor.txt");
            http_response_code(200);
        }
        else{
            //SENAO
            echo "parametro invalido";
            http_response_code(400);
        }
    }else if($_SERVER['REQUEST_METHOD']=="POST"){ 
        //PEDIDO POST
        if(isset($_POST['valor']) and isset($_POST['hora']) and isset($_POST['nome']) and isset($_POST['divisao']) and file_exists("files/".$_POST['divisao']."/".$_POST['nome'])){
            //COLOCA A INFORMACAO NOS FICHEIROS SE OS PARAMETROS FOREM VALIDOS
            file_put_contents("files/".$_POST['divisao']."/".$_POST['nome']."/valor.txt",$_POST['valor']);
            file_put_contents("files/".$_POST['divisao']."/".$_POST['nome']."/hora.txt",$_POST['hora']);
            file_put_contents("files/".$_POST['divisao']."/".$_POST['nome']."/log.txt",$_POST['hora'].";".$_POST['valor'].PHP_EOL,FILE_APPEND);
            http_response_code(200);
        }
        else if (isset($_FILES['imagem']) and isset($_POST['hora'])){
                //separa o nome do ficheiro da extensao
                $ficheiro = explode(".",$_FILES['imagem']['name']);
                
                //so vai receber ficheros com no maximo 1mb e com extencao .jpg ou .png
                if ($_FILES['imagem']['size'] <= 1000000 and ($ficheiro[1] == 'png' or $ficheiro[1] == 'jpg')) {
                    print_r($_FILES['imagem']);
                    move_uploaded_file($_FILES['imagem']['tmp_name'],'images/webcam.jpg');
                    file_put_contents("files/sala/imagem/hora.txt", $_POST['hora']);
                    file_put_contents("files/sala/imagem/log.txt", $_POST['hora'] . ";" . PHP_EOL, FILE_APPEND);
                }
                else {//caso receba uma imagem maior que o limite ou de outras extencoes
                    echo "Imagem não pode ser processada pelo servidor";
                    http_response_code(403);
                }
            }else{
                // caso nao tenha sido enviada nenhuma imagem ou caso os parametros nao estejam definidos
                echo "Imagem não enviada ou parametros invalidos";
                http_response_code(400);
            }
        }
    else{
        // OUTROS 
        echo "metodo nao permitido";
        http_response_code(403);
    }
    
?>