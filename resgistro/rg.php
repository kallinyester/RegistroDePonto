<?php

date_default_timezone_set('America/Sao_Paulo');

require('C:\xampp\htdocs\CH2\DAO\Conexao.php');
require('C:\xampp\htdocs\CH2\DAO\funcionariosDAO.php');
require('C:\xampp\htdocs\CH2\DAO\funcionarios.php');
require('C:\xampp\htdocs\CH2\DAO\registrosDAO.php');
require('C:\xampp\htdocs\CH2\DAO\registros.php');

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
    $hora_inicio = "17:49:59";
    $hora_final = "19:59:59";

    if (empty($funcionarios_id)) {
        $mensagem = "";//"Funcionário não selecionado.";
    } else {
        $registro->set("funcionarios_id", $funcionarios_id);

        if ($tipo == "Confirmar Presença") {
            // não permite que o usuário confirme a presença depois das 19h
            if (date('w') != 2){
                $mensagem = "Hoje não é terça-feira! <br><img src='GIFdesconfiado.gif'> ";
            }
            // não permite que o usuário confirme a presença antes das 17h50
            elseif (strtotime($hora_atual) <= strtotime($hora_inicio)) {
                $mensagem = "A RG ainda não começou! <br><img src='GIFdesconfiado.gif'> ";
            }
            // não permite que o usuário confirme a presença em outros dias da semana
            elseif (strtotime($hora_atual) >= strtotime($hora_final)) {
                $mensagem = "A RG já acabou ou você chegou no final!";
            } 
            else {
                $registro->set("hora_entrada", $hora_atual);
                $mensagem = $registro->funcionarios_presenca_rg();
                ?>
                <!DOCTYPE html>
                <html lang="pt-br">
                <head>
                    <meta charset="UTF-8">
                    <title>Entrada</title>
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
            }
        } else {
            $mensagem = "Ação inválida.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" type="image/png" href="https://i.postimg.cc/NMjbycNV/AdeApoio.png">
    <meta charset="UTF-8">
    <title>Presença em RG</title>
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
    <img src="https://i.postimg.cc/8cPHpD6f/design-site.png" alt="Reunião Geral" class="rg-img">

        <?php if (!empty($mensagem)): ?>
            <p style="color: red; font-weight: bold;"><?= $mensagem ?></p>
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
                <?php if (!empty($funcionarios)) { ?>
                    <?php foreach ($funcionarios as $funcionarios){ ?>
                        <option value="<?= $funcionarios['id'] ?>">
                            <?= htmlspecialchars($funcionarios['nome']) ?>
                        </option>
                    <?php }
                      } ?>
            </select>
            <div class="botoes">
                <button type="submit" name="tipo" value="Confirmar Presença">Confirmar Presença</button>
            </div>
        </form>
    </div>
    <img src="https://i.postimg.cc/13H5gqZf/Apoio.png" alt="Apoio" class="apoio-img">

</body>
</html>