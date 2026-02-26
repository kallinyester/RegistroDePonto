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

    $objFuncionario = new funcionarios(); 
    $objFuncionario->set("situacao", $situacaoSelecionada);
    $funcionarios = $objFuncionario->busca_por_situacao();
    
    $objEvento = new funcionariosDAO();
    $eventos = $objEvento->buscar_evento();
}

// Processamento do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registro = new registros();

    $funcionarios_id = $_POST["funcionarios_id"] ?? null;
    $tipo = $_POST["tipo"] ?? null;

    $evento_raw = $_POST["evento"] ?? null;

    if ($evento_raw && strpos($evento_raw, '|') !== false) {
        list($evento_id, $data) = explode('|', $evento_raw);
    } else {
        $evento_id = null;
        $data = null;
    }

    $hora_inicio = $_POST["hora_inicio"] ?? null;
    $hora_atual = date("H:i:s");
    $hoje = date("Y-m-d");

    if (empty($funcionarios_id)) {
        $mensagem = "";//"Funcionário não selecionado.";
    } else {
        $registro->set("funcionarios_id", $funcionarios_id);

        if ($tipo == "Confirmar Presença") {
            if (strtotime($hoje) != strtotime($data)){ // não permite que o usuário confirme a presença em outros dias da semana
                $mensagem = "O evento não é hoje! <br><img src='GIFdesconfiado.gif'> ";
            }
            elseif (strtotime($hora_atual) <= strtotime($hora_inicio)) { // não permite que o usuário confirme a presença antes do inicio do evento
                $mensagem = "O evento ainda não começou! <br><img src='GIFdesconfiado.gif'>";
            }
            else {
                $registro->set("evento_id", $evento_id);
                $registro->set("funcionarios_id", $funcionarios_id);
                $registro->set("data", $data);
                $registro->set("hora_entrada", $hora_atual);
                $mensagem = $registro->funcionarios_presenca_evento();
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
    <title>Registrar Presença</title>
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
        <img src="https://i.postimg.cc/rwXgqf5v/eventos-Apoio.png" alt="Eventos Apoio" class="rg-img">

        <?php 
        if (!empty($mensagem)): ?>
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

            <select name="evento">
                <option value="">Selecione o Evento</option>
                <?php if (!empty($eventos)) : ?>
                    <?php foreach ($eventos as $e): ?>
                        <option value="<?= $e['id'] . '|' . $e['data'] ?>">
                            <?php
                                $f = strtotime($e['data']);
                                $dt_f = date("d/m/Y", $f);
                            ?>
                            (<?= $dt_f ?>)
                            <?= htmlspecialchars($e['hora_inicio']) ?>h :
                            <?= htmlspecialchars($e['evento']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <div class="botoes">
                <button type="submit" name="tipo" value="Confirmar Presença">Confirmar Presença</button>
            </div>
        </form>
    </div>
    
    <!-- Imagem da Apoio abaixo do container -->
    <img src="https://i.postimg.cc/13H5gqZf/Apoio.png" alt="Apoio" class="apoio-img">

</body>
</html>