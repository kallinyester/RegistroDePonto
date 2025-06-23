<?php

require('C:\xampp\htdocs\RegistroPonto\DAO\Conexao.php');
require('C:\xampp\htdocs\RegistroPonto\tokens.php');

$objeto = new Conexao();
$verificaSQL = "SELECT * FROM tokens;";
$objeto->set("sql", $verificaSQL);
$verifica_resultado = $objeto->query($verificaSQL);

// se tiver tokens
if($verifica_resultado->num_rows != 0){
    
    $row = $verifica_resultado->fetch_assoc();
    $token = $row["token"];

    $tokens = new tokens();
    $tokens->deleta_token($token);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="icon" type="image/png" href="https://i.postimg.cc/NMjbycNV/AdeApoio.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="refresh" content="320">
    <title>Painel de QrCode</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            color: white;
            background-color:rgb(40, 40, 88);
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .painel-acesso {
            background-color: #f0f4f8;
            display: flex;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            height: 500px;
        }

        .logo {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .botoes-acesso {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        a {
            text-decoration: none;
            vertical-align: auto;
            flex: 1;
            padding: 15px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .painel_visu {
            color: white;
            background-color: rgb(40, 41, 48);
        }

        .painel_interno {
            background-color: rgb(175, 175, 175);
        }

        a{
            text-align: center;
        }

        .botoes {
            display: grid;
            gap: 20px;
            justify-content: center;
        }

        .sede {
            background-color:rgb(91, 83, 129);
            color: white;
        }
        
        .rg{
            background-color:rgb(44, 33, 93);
            color: white;
        }

        .evento {
            background-color:rgb(0, 38, 82);
            color: white;
        }

        a:hover {
            background-color:rgb(191, 209, 235);
            color: black;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }   

        .painel_interno:link, .painel_interno:visited{
            color: black;
        }


        @media (max-width: 768px) {
            .painel-acesso {
                flex-direction: column;
                height: auto;
            }

            .logo {
                height: 200px;
            }

            .botoes-acesso {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
  <div class="painel-acesso">
    <div class="logo">
      <img src="https://i.postimg.cc/13H5gqZf/Apoio.png" alt="Login Ilustração" style="width: 300px;" />
    </div>
    <div class="botoes-acesso">
        <div class="botoes">
            <a href="painel/painel_visualizacao.php" target="_blank" role="button" class="painel_visu">Acessar Painel de Visualização</a>
            <a href="painel/efetivos.php" target="_blank" role="button" class="painel_interno">Acessar Painel Interno</a>
            <a href="qrcode_sede.php" target="_blank" role="button" class="sede">QrCode: Registro de Ponto</a>
            <a href="qrcode_rg.php" target="_blank" role="button" class="rg">QrCode: Registro de Presença em RG</a>
            <a href="qrcode_evento.php" target="_blank" role="button" class="evento">QrCode: Registro de Presença em Evento</a>
            <p style="color: black; text-align: center;">Aviso: Não feche essa janela!</p>
        </div>
    </div>
  </div>
</body>
</html>