<?php

date_default_timezone_set('America/Sao_Paulo');

include_once("Conexao.php");
include_once("funcionariosDAO.php");
include_once("funcionarios.php");
include_once("registrosDAO.php");
include_once("registros.php");

$objeto = new funcionarios;
$efetivos = $objeto->efetivos_buscar();

if (!isset($_GET['token'])) {
    die("Token não informado.");
}

$token = $_GET['token'];
$con = new Conexao();

// Verifica se o token existe e ainda não foi usado
$sql = "SELECT * FROM tokens WHERE token = '$token' AND usado = 0";
$con->set("sql", $sql);
$resultado = $con->query();

if ($resultado->num_rows == 0) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Token Inválido</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                margin: 0;
                height: 100vh;
                background-color: #f8f9fa;
                font-family: Arial, sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .mensagem-erro {
                background-color: #fff;
                padding: 30px 20px;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                text-align: center;
                max-width: 90%;
                color: #c0392b;
                font-size: 20px;
            }
        </style>
    </head>
    <body>
        <div class="mensagem-erro">
            <p><strong>Token inválido ou já utilizado.</strong></p>
        </div>
    </body>
    </html>
    
    <?php
    exit;
}

$mensagem = "";

// Processamento do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registro = new registros();

    $token = $_POST["token"] ?? '';
    $con = new Conexao();

    // Verifica se o token ainda é válido (na hora do POST)
    $sql = "SELECT * FROM tokens WHERE token = '$token' AND usado = 0";
    $con->set("sql", $sql);
    $resultado = $con->query();

    if ($resultado->num_rows == 0) {
        $mensagem = "Token inválido ou já utilizado.";
    } else {
        $efetivos_id = $_POST["efetivos_id"] ?? null;
        $tipo = $_POST["tipo"] ?? null;
        $hora_atual = date("H:i:s");

        if (empty($efetivos_id)) {
            $mensagem = "Funcionário não selecionado.";
        } else {
            $registro->set("efetivos_id", $efetivos_id);

            if ($tipo == "Entrada") {
                $registro->set("hora_entrada", $hora_atual);
                $mensagem = $registro->efetivos_entrada();
            } elseif ($tipo == "Saída") {
                $registro->set("hora_saida", $hora_atual);
                $mensagem = $registro->efetivos_saida();
            } else {
                $mensagem = "Ação inválida.";
            }

            // Marca token como usado apenas se o registro foi bem-sucedido
            if (
                strpos($mensagem, "Você registrou a sua entrada!") !== false ||
                strpos($mensagem, "Você registrou a sua saída e o total de horas do dia!") !== false
            ) {
                $updateToken = "UPDATE tokens SET usado = 1 WHERE token = '$token'";
                $con->set("sql", $updateToken);
                $con->query();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>Registrar Ponto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e3eaf3;
            margin: 0;
            padding: 0; 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px 20px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            text-align: center;
            margin-bottom: 30px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 25px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .botoes {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        button {
            flex: 1;
            padding: 12px 0;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            background-color:rgb(44, 33, 93);
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .apoio-img {
            max-width: 180px;
            width: 50%;
            height: auto;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registro de Ponto</h2>
        
        <?php if (!empty($mensagem)): ?>
            <p style="color: green; font-weight: bold;"><?= $mensagem ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <select name="efetivos_id">
                <option value="">Selecione um funcionário</option>
                <?php foreach ($efetivos as $efetivos): ?>
                    <option value="<?= $efetivos['id'] ?>">
                        <?= htmlspecialchars($efetivos['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="botoes">
                <button type="submit" name="tipo" value="Entrada">Entrada</button>
                <button type="submit" name="tipo" value="Saída">Saída</button>
            </div>
        </form>
    </div>
    
    <!-- Imagem de apoio abaixo do container -->
    <img src="https://i.postimg.cc/13H5gqZf/Apoio.png" alt="Apoio" class="apoio-img">

</body>
</html>