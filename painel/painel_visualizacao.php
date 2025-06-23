<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require('C:\xampp\htdocs\RegistroPonto\DAO\Conexao.php');
$objeto = new Conexao();
$conn = $objeto->getConnection();

// UTIL: Função para formatar a semana (seg a sáb)
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


// ✅ Presença na última terça-feira
$terca = new DateTime('last tuesday');
$data_terca = $terca->format('Y-m-d');
$data_terca_br = $terca->format('d-m-Y');

// Filtros RG
$filtro_situacao_rg = $_POST['situacao_rg'] ?? '';
$filtro_presenca_rg = $_POST['presenca_rg'] ?? '';

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


// Filtros Horas
$filtro_situacao_horas = $_POST['situacao_horas'] ?? '';
$filtro_semana = $_POST['semana'] ?? '';
$filtro_ano = $_POST['ano'] ?? '';

// Consulta presença RG

$sql_rg = "
    SELECT f.id, f.nome, f.situacao,
           DATE_FORMAT('$data_sql', '%d-%m-%Y') AS data,
           IF(r.hora_entrada IS NOT NULL, 'Presente', 'Ausente') AS presenca
    FROM funcionarios AS f
    LEFT JOIN funcionarios_rg r
        ON f.id = r.funcionarios_id AND r.data = '$data_sql'
    WHERE 1 = 1
";

if ($filtro_situacao_rg) {
    $sql_rg .= " AND f.situacao = '" . $conn->real_escape_string($filtro_situacao_rg) . "'";
}
if ($filtro_presenca_rg) {
    $pres = $filtro_presenca_rg === "Presente" ? "IS NOT NULL" : "IS NULL";
    $sql_rg .= " AND r.hora_entrada $pres";
}

$sql_rg .= "
    ORDER BY f.situacao ASC, f.nome ASC
";

$objeto->set("sql", $sql_rg);
$rg = $objeto->query($sql_rg)->fetch_all(MYSQLI_ASSOC);

// Consulta total horas por semana

$sql_horas = "
    SELECT f.id, f.nome, f.situacao,
        WEEK(s.data, 1) AS semana,
        YEAR(s.data) AS ano,
        SEC_TO_TIME(SUM(TIME_TO_SEC(s.total_horas_dia))) AS total_horas
    FROM funcionarios f
    LEFT JOIN funcionarios_sede s 
        ON f.id = s.funcionarios_id
    WHERE 1 = 1
";

if ($filtro_situacao_horas) {
    $sql_horas .= " AND f.situacao = '" . $conn->real_escape_string($filtro_situacao_horas) . "'";
}
if ($filtro_semana && $filtro_ano) {
    $sql_horas .= " AND WEEK(s.data, 1) = " . intval($filtro_semana);
    $sql_horas .= " AND YEAR(s.data) = " . intval($filtro_ano);
}

$sql_horas .= "
    GROUP BY f.id, semana, ano
    ORDER BY f.situacao ASC, f.nome ASC
";

$objeto->set("sql", $sql_horas);
$horas = $objeto->query($sql_horas)->fetch_all(MYSQLI_ASSOC);

// Buscar semanas e anos disponíveis para o filtro
$sql_disponiveis = "SELECT DISTINCT WEEK(data, 1) AS semana, YEAR(data) AS ano
                    FROM funcionarios_sede
                    ORDER BY ano DESC, semana DESC ;";
$objeto->set("sql", $sql_disponiveis);
$disponiveis = $objeto->query($sql_disponiveis)->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" type="image/png" href="https://i.postimg.cc/NMjbycNV/AdeApoio.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Visualização</title>
    <style>
        body {
            background-color: #f0f4f8;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            align-items: flex-start;
        }

        .table-box {
            flex: 1;
            min-width: 300px;
            background-color: #fff;
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .table-box.active {
            z-index: 10;
        }

        .filtro-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            background-color: rgb(175, 215, 255);
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        .filtro-form {
            display: none;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .table-box.show-filtro .filtro-form {
            display: block;
        }

        .tabela-wrapper {
            max-height: 400px;
            overflow-x: auto;
            overflow-y: auto;
            transition: max-height 0.3s ease;
        }

        .tabela-wrapper.expandida {
            max-height: 1000px;
        }

        .expand-btn {
            background-color: rgb(0, 50, 101);
            color: white;
            border: none;
            padding: 5px 7px;
            border-radius: 6px;
            margin-top: 3px;
            cursor: pointer;
        }

        select {
            border-radius: 6px;
            width: 130px;
            height: 29px;
            font-size: 14px;
        }
        table td, table th {
            max-width: 130px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        thead{
            font-size: 18px;
        }
        
        /* Estilo do cabeçalho fixo */
        .container-topo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            background-color: #344065;
            flex-wrap: nowrap;
            gap: 8px;
        }

        .logo {
            height: 70px;
            width: auto;
            flex-shrink: 0;
        }

        .btn-login {
            background-color: #ffffff;
            color: #344065;
            padding: 6px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .btn-login:hover {
            background-color: #e0e0e0;
        }

        /* Estilo responsivo */
        @media (max-width: 768px) {
            .container-topo {
                align-items: center;
                gap: 8px;
                text-align: center;
            }

            .logo{
                height: 40px;
            }

            .btn-login {
                width: 100%;
                max-width: 50px;
            }

            .table-box {
                width: 100%;
            }

            .filtro-btn {
                padding: 6px;
            }

            select {
                width: 100%;
                margin-top: 5px;
            }

            .tabela-wrapper {
                max-width: 100%;
                overflow-x: auto;
            }

        }
    </style>
</head>
<body>
    <header class="topo">
    <div class="container-topo">
        <a href="painel.php"><img src="https://i.postimg.cc/Bbfd4KhN/painel-Apoio.png" alt="Logo" class="logo"></a>
        <a href="login.php" target="_blank" class="btn-login">Login</a>
    </div>
    </header>
    
    <div class="container">
        <!-- TABELA RG -->
        <div class="table-box" id="box-rg">
            <h2>📌 Presença na RG</h2>
            <button class="filtro-btn" onclick="toggleFiltro('box-rg')"><img src="https://cdn-icons-png.flaticon.com/512/107/107799.png" style="max-width: 20px; max-height:20px;"></button>
            <form method="post" class="filtro-form">
                <!-- Filtros da RG aqui -->
                <input type="text" id="busca-nome-rg" placeholder="Digite um nome" style="border-radius: 6px; width: 150px; height: 24px; font-size: 14px;">

                <select name="situacao_rg">
                    <option value="">Situação:</option>
                    <option <?= $filtro_situacao_rg == 'Efetivo' ? 'selected' : '' ?>>Efetivo</option>
                    <option <?= $filtro_situacao_rg == 'Trainee' ? 'selected' : '' ?>>Trainee</option>
                </select>

                <select name="presenca_rg">
                    <option value="">Presença:</option>
                    <option <?= $filtro_presenca_rg == 'Presente' ? 'selected' : '' ?>>Presente</option>
                    <option <?= $filtro_presenca_rg == 'Ausente' ? 'selected' : '' ?>>Ausente</option>
                </select>

                <input type="date" name="data_rg" value="<?= $data_obj->format('Y-m-d') ?>" style="border-radius: 6px; width: 100px; height: 27px; font-size: 14px;">
                <button type="submit" style="background-color:rgb(175, 215, 255); border-radius: 6px; width: 100px; height: 31px; font-size: 14px;">Aplicar</button>
            </form>
            
            <div class="tabela-wrapper" id="wrapper-rg">
                <table id="tabela_rg" class="display" style="width:100%;">
                    <!-- Tabela RG aqui -->
                    <form method="post">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Situação</th>
                            <th>Data</th>
                            <th>Presença</th>
                        </tr>
                    </thead>
                    </form>
                    <tbody>
                        <?php foreach ($rg as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nome']) ?></td>
                                <?php
                                if($row['situacao'] == "efetivo"){
                                    echo "<td style='text-align: center; border-radius: 10px; background-color:rgb(38, 38, 103); color: white;s'>Efetivo</td>";
                                } else {
                                    echo "<td style='text-align: center; border-radius: 10px; background-color:rgb(186, 209, 214);'>Trainee🌱</td>";
                                }
                                ?>
                                <td style="text-align: center; border-radius: 10px; background-color:rgb(170, 170, 170);"><?= $row['data'] ?></td>
                                <?php 
                                if($row['presenca'] == 'Presente'){
                                    echo "<td style='text-align: center; color:rgb(255, 255, 255); background-color:rgb(8, 160, 0); border-radius: 10px;'>Presente</td>";
                                } else{
                                    echo "<td style='text-align: center; color:white; background-color:rgb(212, 65, 65); border-radius: 10px;'>Ausente</td>";
                                }
                                ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button class="expand-btn" onclick="toggleTamanho('wrapper-rg', this)">Ver mais</button>
        </div>

        <!-- TABELA HORAS -->
        <div class="table-box" id="box-horas">
            <h2>⏱️ Total de Horas Semanais</h2>
            <button class="filtro-btn" onclick="toggleFiltro('box-horas')"><img src="https://cdn-icons-png.flaticon.com/512/107/107799.png" style="max-width: 20px; max-height:20px;"></button>
            <form method="post" class="filtro-form">
                <!-- Filtros da Horas aqui -->
                <input type="text" id="busca-nome-horas" placeholder="Digite um nome" style="border-radius: 6px; width: 150px; height: 24px; font-size: 14px;">

                <select name="situacao_horas">
                    <option value="">Situação:</option>
                    <option <?= $filtro_situacao_horas == 'Efetivo' ? 'selected' : '' ?>>Efetivo</option>
                    <option <?= $filtro_situacao_horas == 'Trainee' ? 'selected' : '' ?>>Trainee</option>
                </select>

                <select name="semana">
                    <option value="">Semana:</option>
                    <?php foreach ($disponiveis as $sem): ?>
                        <option value="<?= $sem['semana'] ?>" <?= $sem['semana'] == $filtro_semana ? 'selected' : '' ?>>
                            <?= formatarSemana($sem['ano'], $sem['semana']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="ano" required>
                    <option value="">Ano:</option>
                    <?php foreach (array_unique(array_column($disponiveis, 'ano')) as $ano): ?>
                        <option value="<?= $ano ?>" <?= $ano == $filtro_ano ? 'selected' : '' ?>><?= $ano ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" style="background-color:rgb(175, 215, 255); border-radius: 6px; width: 100px; height: 30px; font-size: 14px;">Aplicar</button>
            </form>
            
            <div class="tabela-wrapper" id="wrapper-horas">
                <table id="tabela_horas" class="display" style="width:100%">
                    <!-- Tabela Horas aqui -->
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Situação</th>
                            <th>Semana</th>
                            <th>Ano</th>
                            <th>Total de Horas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($horas as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nome']) ?></td>
                                <?php
                                if($row['situacao'] == "efetivo"){
                                    echo "<td style='text-align: center; border-radius: 10px; background-color:rgb(38, 38, 103); color: white;s'>Efetivo</td>";
                                } else {
                                    echo "<td style='text-align: center; border-radius: 10px; background-color:rgb(186, 209, 214);'>Trainee🌱</td>";
                                }
                                ?>

                                <td style="text-align: center; border-radius: 10px; background-color:rgb(221, 221, 221);"><?= formatarSemana($row['ano'], $row['semana']) ?></td>
                                
                                <td style="text-align: center; background-color: rgb(202, 204, 255); border-radius:10px;"><?= $row['ano'] ?></td>
                                
                                <?php
                                if($row['total_horas'] >= "04:00:00"){
                                    ?>
                                    <td style="text-align: center; background-color: rgb(96, 251, 186); border-radius:10px;"><?= $row['total_horas'] ?></td>
                                    <?php
                                } elseif($row['total_horas'] > "02:00:00"){
                                    ?>
                                    <td style="text-align: center; background-color: rgb(235, 251, 96); border-radius:10px;"><?= $row['total_horas'] ?></td>
                                    <?php
                                } else {
                                    ?>
                                    <td style="text-align: center; background-color: rgb(255, 127, 127); border-radius:10px;"><?= $row['total_horas'] ?></td>
                                    <?php
                                }
                                ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button class="expand-btn" onclick="toggleTamanho('wrapper-horas', this)">Ver mais</button>
        </div>
    </div>

    <script>

        document.getElementById('busca-nome-rg').addEventListener('input', function() {
        const termo = this.value.toLowerCase();
        const linhas = document.querySelectorAll('#tabela_rg tbody tr');

        linhas.forEach(linha => {
            const nome = linha.querySelector('td').textContent.toLowerCase();
            if (nome.includes(termo)) {
            linha.style.display = '';
            } else {
            linha.style.display = 'none';
            }
        });
        });

        document.getElementById('busca-nome-horas').addEventListener('input', function() {
        const termo = this.value.toLowerCase();
        const linhas = document.querySelectorAll('#tabela_horas tbody tr');

        linhas.forEach(linha => {
            const nome = linha.querySelector('td').textContent.toLowerCase();
            if (nome.includes(termo)) {
            linha.style.display = '';
            } else {
            linha.style.display = 'none';
            }
        });
        });

        function toggleFiltro(id) {
            const box = document.getElementById(id);
            box.classList.toggle('show-filtro');
            box.classList.toggle('active');

            // Desativa a outra, se estiver ativa
            document.querySelectorAll('.table-box').forEach(el => {
                if (el.id !== id) {
                    el.classList.remove('show-filtro');
                    el.classList.remove('active');
                }
            });
        }

        function toggleTamanho(wrapperId, btn) {
            const wrapper = document.getElementById(wrapperId);
            wrapper.classList.toggle('expandida');

            if (wrapper.classList.contains('expandida')) {
                btn.textContent = 'Ver menos';
            } else {
                btn.textContent = 'Ver mais';
            }
        }

        $(document).ready(function() {
            $('table').DataTable({
                language: {
                    "search": "Pesquisar:",
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "Nenhum registro encontrado",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Nenhum registro disponível",
                    "infoFiltered": "(filtrado de _MAX_ registros totais)",
                    "paginate": {
                        "first":      "Primeiro",
                        "last":       "Último",
                        "next":       "Próximo",
                        "previous":   "Anterior"
                    }
                }
            });
        });
    </script>

</body>
</html>
