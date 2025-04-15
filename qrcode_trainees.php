<?php

include_once("Conexao.php");
include_once("tokensDAO.php");
include_once("tokens.php");
include_once("phpqrcode/qrlib.php");

$objeto = new tokens();
$token = $objeto->T_gerar_token();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="icon" type="image/png" href="https://i.postimg.cc/NMjbycNV/AdeApoio.png">
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10">
    <title>Painel de QR Code Trainees</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 20px;
            background-color:rgb(248, 240, 240);
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .qr {
            width: 100%;
            max-width: 320px;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
        }

        .footer {
            width: 100%;
            max-width: 260px;
            height: auto;
            opacity: 0.85;
        }

        p {
            font-size: 18px;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>Escaneie para registrar seu ponto</h1>
    <div class="container">
        <p>Trainee, use seu celular para registrar o ponto</p>
        <img class="qr" src="qrcode.png" alt="QR Code de Registro">
        <img class="footer" src="https://i.postimg.cc/13H5gqZf/Apoio.png" alt="Logo rodapé">
    </div>
</body>
</html>