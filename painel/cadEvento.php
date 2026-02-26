<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('C:\xampp\htdocs\CH2\verificaLogin.php');
include_once('C:\xampp\htdocs\CH2\DAO\Conexao.php');
include_once('C:\xampp\htdocs\CH2\DAO\registrosDAO.php');
include_once('C:\xampp\htdocs\CH2\DAO\registros.php');

$msg = "";

// Lógica de Cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $func = new registros();
    $func->set("evento", $_POST['evento']);

    if (!empty($_POST['evento'])) {
        $func->set("data", $_POST['data']);
        $func->set("hora_inicio", $_POST['hora_inicio']);
        $msg = $func->cadastrar_evento();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" type="image/png" href="https://apoioconsultoriajunior.com.br/ponto/imagens/AdeApoio.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Interno</title>
    <style>
        .container { 
            max-width: 800px; 
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
            border-radius: 6px; 
            width: 100px; 
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
            width: 100px;
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

        .search-container {
            position: absolute;
            text-align: right;
            right: 458px;
            top: 140px;
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
                margin-left: 0px;
                padding-left: 10px;
                padding-top: 20px;
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
        <a href="efetivos.php"><img src="https://apoioconsultoriajunior.com.br/ponto/imagens/painel.png" alt="Logo" class="logo"></a>
        <a href="../qrcode.php" class="btn-qrcode">QrCode</a>
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
            <h2>Cadastrar Evento</h2>
            <div class="container">
                <?php if ($msg): ?>
                    <div class="msg"><?= $msg ?></div>
                <?php endif; ?>

                <!-- Formulário -->
                <form method="POST">
                    
                    Insira o nome do Evento:<br><input type="text" name="evento" id="evento" placeholder="Evento:" required><br>

                    Data:<br><input type="date" name="data" id="data" required><br>

                    Hora de Início:<br><input type="time" name="hora_inicio" id="hora_inicio" required><br><br>

                    <input type="submit" value="Salvar">
                </form>

                <!-- Tabela -->
            </div>
        </main>
    </div>

<script>

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }


    
</script>

</body>
</html>