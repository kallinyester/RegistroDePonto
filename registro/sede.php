<?php

date_default_timezone_set('America/Sao_Paulo');

require('C:\xampp\htdocs\RegistroPonto\DAO\Conexao.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\funcionariosDAO.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\funcionarios.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\registrosDAO.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\registros.php');
require('C:\xampp\htdocs\RegistroPonto\tokens.php');

if (!isset($_GET['token'])) {
    die("Token não informado.");
}

$token = $_GET["token"];
$tokens = new tokens();
$resultado = $tokens->verifica_token($token);

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
                font-family: 'Poppins', sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .mensagem-erro {
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

if (!empty($_POST['situacao']) && empty($_POST['tipo'])) {
    // Carrega funcionários apenas se o usuário mudou a situação e ainda não registrou ponto
    $situacaoSelecionada = $_POST["situacao"];

    $objFuncionario = new funcionarios(); // Ou o nome da sua classe
    $objFuncionario->set("situacao", $situacaoSelecionada);
    $funcionarios = $objFuncionario->busca_por_situacao();
}

// Processamento do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registro = new registros();

    $funcionarios_id = $_POST["funcionarios_id"] ?? null;
    $tipo = $_POST["tipo"] ?? null;
    $hora_atual = date("H:i:s");

    if (empty($funcionarios_id)) {
        $mensagem = "";//"Funcionário não selecionado.";
    } else {
        $registro->set("funcionarios_id", $funcionarios_id);

        if ($tipo == "Entrada") {
            $registro->set("hora_entrada", $hora_atual);
            $mensagem = $registro->funcionarios_entrada();
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
                        font-family: 'Poppins', sans-serif;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                </style>
            </head>
            <body>
                <div class="mensagem-erro">
                    <?php if (!empty($mensagem)): ?>
                        <p style="color: green; font-weight: bold;"><?= $mensagem ?></p>
                    <?php endif; ?>
                </div>
            </body>
            </html>
            
            <?php
            exit;
        } elseif ($tipo == "Saída") {
            $registro->set("hora_saida", $hora_atual);
            $mensagem = $registro->funcionarios_saida();
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
                        font-family: 'Poppins', sans-serif;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                </style>
            </head>
            <body>
                <div class="mensagem-erro">
                    <?php if (!empty($mensagem)): ?>
                        <p style="color: green; font-weight: bold;"><?= $mensagem ?></p>
                    <?php endif; ?>
                </div>
            </body>
            </html>
            
            <?php
            exit;
        } else {
            $mensagem = "Ação inválida.";
        }

        // Exclui o token usado apenas se o registro foi bem-sucedido
        if (
            strpos($mensagem, "Você registrou a sua entrada!") !== false ||
            strpos($mensagem, "Você registrou a sua saída e o total de horas do dia!") !== false
        ) {
            $token = $_GET["token"];
            $tokens = new tokens();
            $tokens->deleta_token($token);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" type="image/png" href="https://i.postimg.cc/NMjbycNV/AdeApoio.png">
    <meta charset="UTF-8">
    <title>Registrar Ponto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="site.css">
    <style>
        .container .rg-img {
            width: 100%;
            text-align: center;
            border-radius: 10px;
            max-width: 400px;
            height: auto;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://i.postimg.cc/630fcKSK/hsede.png" alt="Horário de Sede" class="rg-img">
        
        <?php if (!empty($mensagem)): ?>
            <p style="color: green; font-weight: bold;"><?= $mensagem ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <select name="situacao" onchange="this.form.submit()">
                <option value="">Você é Efetivo ou Trainee?</option>
                <option value="Efetivo" <?= (isset($_POST['situacao']) && $_POST['situacao'] == 'Efetivo') ? 'selected' : '' ?>>Efetivo</option>
                <option value="Trainee" <?= (isset($_POST['situacao']) && $_POST['situacao'] == 'Trainee') ? 'selected' : '' ?>>Trainee</option>
            </select>
            <select name="funcionarios_id">
                <option value="">Qual o seu nome?</option>
                <?php if (!empty($funcionarios)) : ?>
                    <?php foreach ($funcionarios as $funcionarios): ?>
                        <option value="<?= $funcionarios['id'] ?>">
                            <?= htmlspecialchars($funcionarios['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <div class="botoes">
                <button type="submit" name="tipo" value="Entrada">Entrada</button>
                <button type="submit" name="tipo" value="Saída">Saída</button>
            </div>
        </form>
    </div>
    
    <!-- Imagem da Apoio abaixo do container -->
    <img src="https://i.postimg.cc/13H5gqZf/Apoio.png" alt="Apoio" class="apoio-img">

</body>
</html>