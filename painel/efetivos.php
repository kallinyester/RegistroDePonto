<?php

include_once('C:\xampp\htdocs\CH2\verificaLogin.php');
include_once('C:\xampp\htdocs\CH2\DAO\Conexao.php');
include_once('C:\xampp\htdocs\CH2\DAO\funcionariosDAO.php');
include_once('C:\xampp\htdocs\CH2\DAO\funcionarios.php');

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
    } else {
        $msg = $func->cadastrar();
    }
}

if (isset($_GET['excluir'])) {
    $func = new funcionarios();
    $func->set("id", $_GET['excluir']);
    $msg = $func->deletar();
}

$dao = new funcionariosDAO();
$funcionarios = $dao->buscar_efetivos();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" type="image/png" href="https://apoioconsultoriajunior.com.br/ponto/imagens/AdeApoio.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Interno</title>
    <script src="efetivos.js"></script>
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
        input[type="text"]{
            width: 500px;
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

        <?php 
        foreach ($funcionarios as $f): 
            $c= $c+1;
        endforeach;
        ?>

        <!-- Conteúdo -->
        <main class="content">
            <h2>Relação de Efetivos (<?= $c ?>)</h2>
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
                <form method="POST">
                    <input type="hidden" name="id" id="id" value="">
                    
                    <input type="text" name="nome" id="nome" placeholder="Nome:" required>

                    <select name="situacao" id="situacao" required>
                        <option value="">Situação:</option>
                        <option value="efetivo">Efetivo</option>
                        <option value="trainee">Trainee 🌱</option>
                    </select>

                    <input type="submit" value="Salvar">
                </form>

                <!-- Tabela -->
                <table id="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Situação</th>
                            <th>Editar</th>
                            <th>Excluir</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($funcionarios as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['nome']) ?></td>
                            <td style='text-align: center; border-radius: 10px; background-color:rgb(38, 38, 103); color: white;'>Efetivo</td>
                            
                            <td style="text-align: center;" onclick="voltarTopo()">
                                <button class="btn-edit" onclick="preencherFormulario('<?= $f['id'] ?>', '<?= addslashes($f['nome']) ?>', '<?= $f['situacao'] ?>')"><img id='img' src='https://icons.veryicon.com/png/o/miscellaneous/linear-small-icon/edit-246.png' alt='Editar'></button>
                            </td>
                            <td style="text-align: center;">
                                <a href="?excluir=<?= $f['id'] ?>" onclick="return confirm('Deseja excluir este funcionário?')">
                                    <button class="btn-delete"><img id='img' src='https://icones.pro/wp-content/uploads/2021/08/icone-x-noir.png' alt='Excluir'></button>
                                </a>
                            </td>
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
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        function limpaUrl() {
            // Pega a URL atual
            const urlAtual = window.location.href;

            // Remove tudo após o '?'
            const urlLimpa = urlAtual.split('?')[0];

            // Substitui a URL atual no histórico do navegador
            window.history.replaceState(null, null, urlLimpa);
        }

        // Executa a função após 1 segundo
        setTimeout(limpaUrl, 1000);
    </script>
</body>
</html>