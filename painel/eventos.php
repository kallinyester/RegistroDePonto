<?php

require('verificaLogin.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\Conexao.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\funcionariosDAO.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\funcionarios.php');

$c = 0;
$msg = "";

// Lógica de Cadastro, Edição e Exclusão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $func = new funcionarios();
    $func->set("nome", $_POST['nome']);
    $func->set("situacao", $_POST['situacao']);

    if (!empty($_POST['id'])) {
        $func->set("id", $_POST['id']);
        $msg = $func->alterar();
    }
}

if (isset($_GET['excluir'])) {
    $func = new funcionarios();
    $func->set("id", $_GET['excluir']);
    $msg = $func->deletar();
}

$dao = new funcionariosDAO();
$evento = $dao->buscar_eventos();


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" type="image/png" href="https://i.postimg.cc/NMjbycNV/AdeApoio.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Interno</title>
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

        .page-content {
            display: flex;
            padding-top: 97px; /* altura do cabeçalho */
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

        #searchInput {
            width: 240px;
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

            a:visited{
                color: white;
                text-decoration: none;
            }
            a:link{
                color: white;
                text-decoration: none;
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
            <h2>Presença em Eventos</h2>
            <form action="">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Pesquisar...">
                </div><br>
            </form>
            <div class="container">
                <?php if ($msg): ?>
                    <div class="msg"><?= $msg ?></div>
                <?php endif; 

                    if($evento != null){
                
                ?>

                <!-- Tabela -->
                <table id="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Situação</th>
                            <th>Evento</th>
                            <th>Data</th>
                            <th>Início do Evento</th>
                            <th>Hora de Entrada</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($evento as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['nome']) ?></td>
                            <?php
                            if($e['situacao'] == "efetivo"){
                                echo "<td style='text-align: center; border-radius: 10px; background-color:rgb(38, 38, 103); color: white;s'>Efetivo</td>";
                            } else {
                                echo "<td style='text-align: center; border-radius: 10px; background-color:rgb(186, 209, 214);'>Trainee🌱</td>";
                            }
                            ?>
                            <td style="text-align: center; border-radius: 10px; background-color:rgb(203, 232, 222);"><?= $e['evento'] ?></td>
                            <?php
                                $f = strtotime($e['data']);
                                $dt_f = date("d/m/Y", $f);
                            ?>
                            <td style="text-align: center; border-radius: 10px; background-color:rgb(221, 221, 221);"><?= $dt_f ?></td>
                            <td style="text-align: center; border-radius: 10px; background-color:rgb(221, 221, 221);"><?= $e['hora_inicio'] ?></td>
                            <td style="text-align: center; border-radius: 10px; background-color:rgb(204, 204, 204);"><?= $e['hora_entrada'] ?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>

            <?php } else {
                echo "Nenhum resultado encontrado.";
            } ?>
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


    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }
    
</script>

</body>
</html>