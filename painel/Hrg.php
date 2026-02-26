<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('C:\xampp\htdocs\CH2\verificaLogin.php');
include_once('C:\xampp\htdocs\CH2\DAO\Conexao.php');
include_once('C:\xampp\htdocs\CH2\DAO\funcionariosDAO.php');
include_once('C:\xampp\htdocs\CH2\DAO\funcionarios.php');
include_once('C:\xampp\htdocs\CH2\DAO\registros.php');
include_once('C:\xampp\htdocs\CH2\DAO\registrosDAO.php');

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $func = new registros();
    $func->set("funcionarios_id", $_POST['id']);
    $func->set("hora_entrada", $_POST['hora_entrada']);
    $func->set("data", $_POST['data_rg']);

    if (!empty($_POST['id'])) {
        $msg = $func->alterar_presenca_rg();
    }
}

$objeto = new Conexao();
$conn = $objeto->getConnection();

// ✅ Presença na última terça-feira
$terca = new DateTime('last tuesday');
$data_terca = $terca->format('Y-m-d');
$data_terca_br = $terca->format('d-m-Y');

// Filtros RG
$filtro_data_raw = $_POST['data_rg'] ?? null;
$data_obj = false;

if ($filtro_data_raw) {
    // Tenta converter como Y-m-d (padrão do input date)
    $data_obj = DateTime::createFromFormat('Y-m-d', $filtro_data_raw);

    // Se falhar, tenta como d-m-Y
    if (!$data_obj) {
        $data_obj = DateTime::createFromFormat('d-m-Y', $filtro_data_raw);
    }
}

// Se ainda assim falhar, usa a última terça-feira
if (!$data_obj) {
    $data_obj = new DateTime();
    $data_obj->modify('last tuesday');
}

$data_sql = $data_obj->format('Y-m-d'); // formato para usar no SQL
$filtro_data = $data_obj->format('d-m-Y'); // formato para exibir

// ✅ Consulta presença RG

$sql_rg = "
    SELECT f.id, f.nome, f.situacao,
           DATE_FORMAT('$data_sql', '%d-%m-%Y') AS data, r.hora_entrada
    FROM funcionarios AS f
    LEFT JOIN funcionarios_rg r
        ON f.id = r.funcionarios_id AND r.data = '$data_sql'
    WHERE 1 = 1
    ORDER BY f.situacao ASC, f.nome ASC
";

$objeto->set("sql", $sql_rg);
$rg = $objeto->query($sql_rg)->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" type="image/png" href="https://apoioconsultoriajunior.com.br/ponto/imagens/AdeApoio.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Interno</title>
    <script src="Hrg.js"></script>
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

        .search-container {
            position: absolute;
            text-align: right;
            right: 458px;
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
            <h2>Relação de Presença em RG</h2>
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

                    <input type="time" id="hora_entrada" name="hora_entrada" step="2">

                    <input type="date" id="data_rg" name="data_rg" value="<?= $data_obj->format('Y-m-d') ?>">

                    <input type="submit" value="Salvar">
                </form>

                <!-- Tabela -->
                <table id="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Situação</th>
                            <th>Data</th>
                            <th>Presença</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rg as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['nome']) ?></td>
                            <?php
                                if($f['situacao'] == "efetivo"){
                                    echo "<td style='text-align: center; border-radius: 10px; background-color:rgb(38, 38, 103); color: white;s'>Efetivo</td>";
                                } else {
                                    echo "<td style='text-align: center; border-radius: 10px; background-color:rgb(186, 209, 214);'>Trainee🌱</td>";
                                }
                                ?>
                                <td style="text-align: center; border-radius: 10px; background-color:rgb(170, 170, 170);"><?= $f['data'] ?></td>
                                <?php 
                                if($f['hora_entrada'] != null){
                                    ?>
                                    <td id="hora_entrada" name="hora_entrada" style='text-align: center; color:rgb(255, 255, 255); background-color:rgb(8, 160, 0); border-radius: 10px;'><?= $f['hora_entrada'] ?></td>
                                    <?php
                                } else{
                                    ?>
                                    <td id='hora_entrada' name='hora_entrada' value="Ausente" style='text-align: center; color:white; background-color:rgb(212, 65, 65); border-radius: 10px;'>Ausente</td>
                                    <td style="text-align: center;" onclick="voltarTopo()">
                                        <button class="btn-edit" onclick="preencherFormulario('<?= $f['id'] ?>', '<?= addslashes($f['nome']) ?>', '<?= $f['situacao'] ?>', '<?= $f['data'] ?>', '<?= $f['hora_entrada'] ?>')"><img id='img' src='https://icons.veryicon.com/png/o/miscellaneous/linear-small-icon/edit-246.png' alt='Editar'></button>
                                    </td>
                            <?php
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