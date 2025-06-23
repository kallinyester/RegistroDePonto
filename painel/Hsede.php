<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require('verificaLogin.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\Conexao.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\funcionariosDAO.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\funcionarios.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\registros.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\registrosDAO.php');

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $func = new registros();
    $func->set("funcionarios_id", $_POST['id']);
    $func->set("data", $_POST['data']);
    $func->set("hora_entrada", $_POST['hora_entrada']);
    $func->set("hora_saida", $_POST['hora_saida']);

    if (!empty($_POST['id'])) {
        $msg = $func->alterar_horas_SEDE();
    }
}

$objeto = new Conexao();
$conn = $objeto->getConnection();

// Função para formatar a semana (seg a sáb)
function formatarSemana($ano, $semana) {
    if (!$ano || !$semana) {
        return '-';
    }

    $dt = new DateTime();
    $dt->setISODate((int)$ano, (int)$semana);
    $inicio = clone $dt;
    $fim = clone $dt;
    $fim->modify('+5 days');

    if ($inicio->format('m') === $fim->format('m')) {
        return $inicio->format('d') . '-' . $fim->format('d/m');
    } else {
        return $inicio->format('d/m') . ' - ' . $fim->format('d/m');
    }
}

// Consulta total horas por semana

$sql_horas = "
    SELECT f.id, f.nome, f.situacao, 
        WEEK(s.data, 1) AS semana,
        YEAR(s.data) AS ano, s.data, s.hora_entrada, s.hora_saida, s.total_horas_dia
    FROM funcionarios f
    LEFT JOIN funcionarios_sede s 
        ON f.id = s.funcionarios_id
    GROUP BY f.id, data
    ORDER BY f.situacao ASC, f.nome ASC
";

$objeto->set("sql", $sql_horas);
$horas = $objeto->query($sql_horas)->fetch_all(MYSQLI_ASSOC);

// Buscar dias disponíveis para o filtro
$sql_disponiveis = "SELECT data
                    FROM funcionarios_sede
                    ORDER BY data DESC ;";
$objeto->set("sql", $sql_disponiveis);
$disponiveis = $objeto->query($sql_disponiveis)->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" type="image/png" href="https://i.postimg.cc/NMjbycNV/AdeApoio.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Interno</title>
    <script src="Hsede.js"></script>
    <style>
        .container { 
            margin: auto; 
            top: 100px;
        }
        table { 
            width: 100%; 
            margin-top: 20px; 
        }
        th { 
            text-align: center;
        }
        input[type="time"], input[type="date"]{
            border-radius: 
            6px; width: 100px; 
            height: 30px; 
            font-size: 14px;
        }
        input[type="text"]{
            width: 300px;
        }
        input[type="text"], select { 
            padding: 8px; 
            border-radius: 6px;
            font-size: 14px;
        }
        input[type="submit"] { 
            background: #2d3e73; 
            color: #fff; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 8px; cursor: pointer; 
        }
        .msg { 
            background: #d4edda; 
            padding: 10px; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
            border-radius: 5px; 
            margin-bottom: 15px; 
        }
        .btn-delete { 
            background: #d9534f; 
            color: white; 
            border: none; 
            padding: 5px 10px; 
            cursor: pointer; 
            border-radius: 5px; 
        }
        img {
            height: 20px;
            width: 20px;
        }
        .btn-edit { 
            background:rgb(255, 239, 63); 
            color: white; 
            border: none; 
            padding: 5px 10px; 
            cursor: pointer;
            border-radius: 5px; 
        }
        body {
            background-color: #f0f4f8;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .container-topo {
            position: fixed;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            background-color: #344065;
            flex-wrap: nowrap;
            gap: 8px;
            z-index: 1000;
            width: 98%;
        }

        .logo {
            height: 80px;
            width: auto;
            flex-shrink: 0;
        }

        .btn-qrcode {
            background-color: #ffffff;
            color: #344065;
            padding: 6px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .btn-qrcode:hover {
            background-color: #e0e0e0;
        }

        .page-content {
            display: flex;
            padding-top: 97px; /* altura do cabeçalho */
        }

        .menu:hover {
            color: white;
            text-decoration: none;
        }

        .menu:link {
            color: white;
            text-decoration: none;
        }
        .menu:visited {
            color: white;
            text-decoration: none;
        }

        /* Sidebar fixa */
        .sidebar {
            color: white;
            position: fixed;
            top: 97px; /* abaixo do cabeçalho */
            left: 0;
            width: 220px;
            height: 100%;
            background-color:rgb(64, 76, 112);
            padding: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar li {
            padding: 10px 20px;
        }

        /* Conteúdo ao lado da sidebar */
        .content {
            margin-left: 250px;
            padding-left: 100px;
            padding-top: 20px;
        }

        /* Esconde o botão em desktop */
        .menu-toggle {
            display: none;
            background-color:rgb(55, 59, 100);
            color: white;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            position: fixed;
            top: 50px;
            width: 100%;
            z-index: 999;
        }

        .search-container {
            position: absolute;
            text-align: right;
            right: 334px;
            top: 140px;
            z-index: 10;
        }

        #searchInput {
            width: 170px;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .container-topo {
                align-items: center;
                gap: 8px;
                text-align: center;
            }
            .logo{
                height: 40px;
            }
            .btn-qrcode {
                width: 100%;
                max-width: 50px;
            }

            .sidebar {
                top: 110px;
                width: 100%;
                height: auto;
                display: none;
            }
            .sidebar ul {
                list-style: none;
                padding: 0;
            }

            .sidebar li {
                padding: 10px 20px;
            }

            .sidebar a {
                color: white;
                text-decoration: none;
            }
            .sidebar.active {
                display: block;
            }

            .content {
                margin-left: 250px;
                padding-left: 100px;
                padding-top: 20px;
            }
            
            .menu-toggle {
                display: block;
                background-color: #e0e0e0;
                width: 100%;
                text-align: center;
                padding: 10px;
                cursor: pointer;
                font-weight: bold;
                border-bottom: 1px solid #ccc;
                position: fixed;
                top: 65px;
                left: 0;
                z-index: 99;
            }
            .content {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
                margin-top: 120px; /* espaço para o cabeçalho + botão */
            }

        }


    </style>
</head>
<body>
    <header class="topo">
    <div class="container-topo">
        <a href="efetivos.php"><img src="https://i.postimg.cc/8PwrT4cn/Painel-Interno-Apoio.png" alt="Logo" class="logo"></a>
        <a href="qrcode.php" target="_blank" class="btn-qrcode">QrCode</a>
    </div>
    </header>

    <div class="page-content">
        <!-- Botão para mobile -->
        <div class="menu-toggle" onclick="toggleSidebar()">☰ Menu</div>

        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
        <ul>
            <li><a class="menu" href="efetivos.php">Relação de Efetivos</a></li>
            <li><a class="menu" href="trainees.php">Relação de Trainees</a></li>
            <li><a class="menu" href="Hsede.php">Horas em Sede</a></li>
            <li><a class="menu" href="Hrg.php">Presença em RG</a></li>
            <li><a class="menu" href="eventos.php">Presença em Eventos</a></li>
            <li><a class="menu" href="cadEvento.php">Cadastrar evento</a></li>
            <li><a class="menu" href="cad.php">Cadastrar membro</a></li> 
        </ul>
        </nav>

        <!-- Conteúdo -->
        <main class="content">
            <h2>Relação de Horas em Sede</h2>
            <form action="">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Pesquisar...">
                </div><br>
            </form>
            <div class="container">
                <?php if ($msg): ?>
                    <div class="msg"><?= $msg ?></div>
                <?php endif; ?>

                <!-- Formulário -->
                <form id="form" method="POST">
                    <input type="hidden" name="id" id="id" value="">
                    
                    <input type="text" name="nome" id="nome" placeholder="Nome:" required>

                    <select name="situacao" id="situacao" required>
                        <option value="">Situação:</option>
                        <option value="efetivo">Efetivo</option>
                        <option value="trainee">Trainee 🌱</option>
                    </select>

                    <input id="data" name="data" type="date">

                    <input type="time" id="hora_entrada" name="hora_entrada" step="2">

                    <input type="time" id="hora_saida" name="hora_saida" step="2">

                    <input type="submit" value="Salvar">
                </form>

                <!-- Tabela -->
                <table id="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Situação</th>
                            <th>Semana</th>
                            <th>Data</th>
                            <th>Entrada</th>
                            <th>Saída</th>
                            <th>Total</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($horas as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['nome']) ?></td>
                            <?php
                                if($f['situacao'] == "efetivo"){
                                    echo "<td style='text-align: center; border-radius: 10px; background-color:rgb(38, 38, 103); color: white;s'>Efetivo</td>";
                                } else {
                                    echo "<td style='text-align: center; border-radius: 10px; background-color:rgb(186, 209, 214);'>Trainee🌱</td>";
                                }
                                ?>

                                <td style="text-align: center; border-radius: 10px; background-color:rgb(221, 221, 221);"><?= formatarSemana($f['ano'], $f['semana']) ?></td>
                                
                                <?php
                                if($f['data'] != null){
                                    $a = strtotime($f['data']);
                                    $dt_f = date("d/m/Y", $a);
                                    
                                    ?>
                                    <td id="data" name="data" style="text-align: center; border-radius: 10px; background-color:rgb(221, 221, 221);"><?= $dt_f ?></td>
                                    <?php
                                }else{
                                    ?>
                                    <td id="data" name="data" style="text-align: center; border-radius: 10px; background-color:rgb(221, 221, 221);"><?= $f['data'] ?></td>
                                    <?php
                                }
                                ?>
                                
                                <?php
                                if($f['hora_entrada'] != null){
                                    ?>
                                    <td id="hora_entrada" name="hora_entrada" style="text-align: center; background-color: rgb(202, 204, 255); border-radius:10px;"><?= $f['hora_entrada'] ?></td>

                                    <td id="hora_saida" name="hora_saida" style="text-align: center; background-color: rgb(202, 204, 255); border-radius:10px;"><?= $f['hora_saida'] ?></td>
                                
                                    <?php                                     
                                    if($f['total_horas_dia'] != null){ ?>
                                        <td style="text-align: center; background-color: rgb(226, 226, 226); border-radius:10px;"><?= $f['total_horas_dia'] ?></td>
                                        <?php  
                                    } else{
                                        ?> 
                                        <td style="text-align: center; background-color: rgb(226, 226, 226); border-radius:10px;"><?= $f['total_horas_dia'] ?></td>
                                        <?php
                                    }  
                                    ?>
                                    <td style="text-align: center;" onclick="voltarTopo()">
                                        <button class="btn-edit" onclick="preencherFormulario('<?= $f['id'] ?>', '<?= addslashes($f['nome']) ?>', '<?= addslashes($f['situacao']) ?>', '<?= $f['data'] ?>', '<?= $f['hora_entrada'] ?>', '<?= $f['hora_saida'] ?>')">
                                            <img id='img' src='https://icons.veryicon.com/png/o/miscellaneous/linear-small-icon/edit-246.png' alt='Editar'>
                                        </button>
                                    </td> 
                                    <?php
                                } else {
                                ?>
                                
                                    <td id="hora_entrada" name="hora_entrada" style="text-align: center; background-color: rgb(202, 204, 255); border-radius:10px;"><?= $f['hora_entrada'] ?></td>

                                    <td id="hora_saida" name="hora_saida" style="text-align: center; background-color: rgb(202, 204, 255); border-radius:10px;"><?= $f['hora_saida'] ?></td>
                                    
                                    <?php                                     
                                    if($f['total_horas_dia'] != null){ ?>
                                        <td style="text-align: center; background-color: rgb(226, 226, 226); border-radius:10px;"><?= $f['total_horas_dia'] ?></td>
                                        <?php  
                                    } else{
                                        ?> 
                                        <td style="text-align: center; background-color: rgb(226, 226, 226); border-radius:10px;"><?= $f['total_horas_dia'] ?></td>
                                        <?php
                                    }    
                                }                             
                                ?>                               
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script>
        document.getElementById('searchInput').addEventListener('input', function () {
            const filtro = this.value.toLowerCase();
            const itens = document.querySelectorAll('#table tbody tr');

            itens.forEach(function (item) {
                const texto = item.textContent.toLowerCase();
                item.style.display = texto.includes(filtro) ? '' : 'none';
            });
        });
    </script>
</body>
</html>