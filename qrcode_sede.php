<?php

require('verificaLogin.php');
require('C:\xampp\htdocs\CH2\phpqrcode/qrlib.php');
require('C:\xampp\htdocs\CH2\DAO\Conexao.php');
require('C:\xampp\htdocs\CH2\qrcodeDAO.php');

$objeto = new tokens;
$token = $objeto->sede_gerar_qrcode();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" type="image/png" href="https://apoioconsultoriajunior.com.br/ponto/imagens/AdeApoio.png">
    <meta charset="UTF-8">
    <title>Painel de QR Code Registro de Ponto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 20px;
            background-color: #f0f4f8;
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
        <p>Use seu celular</p>
        <img class="qr" src="sede_qrcode.png?nocache=<?= time() ?>" alt="QR Code de Registro">
        <img class="footer" src="https://apoioconsultoriajunior.com.br/ponto/imagens/LogoApoio.png" alt="Logo rodapé">
    </div>
</body>
</html>