<?php

date_default_timezone_set('America/Sao_Paulo');

class registrosDAO {
    public $id;
    public $funcionarios_id;
    public $hora_entrada;
    public $hora_saida;
    public $total_horas_dia;

    public $registro_id;

    public $hora_inicio;
    public $evento;
    public $evento_id;

    public $nome;
    public $data;

    public $usuario;
    public $senha;

    public function set($prop, $value) {
        $this->$prop = $value;
    }
    public function get($prop) {
        return isset($this->$prop) ? $this->$prop : null;
    }

    public function cadastrar_evento(){
        $objeto = new Conexao();
        $sql = "INSERT INTO evento (evento, data, hora_inicio)
                VALUES ('$this->evento', '$this->data', '$this->hora_inicio');";
        $objeto->set("sql", $sql);
        $objeto->query($sql);
        return "Evento Cadastrado com Sucesso";
    }

    public function validarLogin() {
        $objeto = new Conexao();
        $SQL = "SELECT usuario FROM usuario WHERE usuario = '$this->usuario' AND senha = '$this->senha'";
        $objeto->set("sql", $SQL);
        $result = $objeto->query($SQL);

        if ($result->num_rows > 0) {
            session_start();
            $_SESSION['login'] = $this->usuario; // Login Completo
            header('Location: ../qrcode.php');
        } else {
            return "Nome e/ou senha incorretos!";
        }
    }

    public function alterar_presenca_rg() {
        $objeto = new Conexao();

        // Verifica se já tem entrada registrada
        $verificaSQL = "SELECT hora_entrada FROM funcionarios_rg 
                        WHERE funcionarios_id = '$this->funcionarios_id'
                        AND data = '$this->data';";
        $objeto->set("sql", $verificaSQL);
        $resultado = $objeto->query($verificaSQL);
        $linha = $resultado->fetch_assoc();

        if (!$linha) {
            $SQL = "INSERT INTO funcionarios_rg (funcionarios_id, data, hora_entrada) 
                    VALUES ('$this->funcionarios_id', '$this->data', '$this->hora_entrada');";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
            return "Você confirmou sua presença!";
        } 
        else {
            return "Presença já confirmada anteriormente.";
        }
    }

    public function alterar_horas_SEDE() {
        $objeto = new Conexao();

        // Cálculo do total de horas
        $semana = date("W", strtotime($this->data));
        $ano = date("Y", strtotime($this->data));

        $hora_entrada = strtotime($this->hora_entrada);
        $hora_saida = strtotime($this->hora_saida);

        if ($hora_saida <= $hora_entrada) {
            return " 
            <style> 
                .msg {
                    color:red; 
                    background:rgb(255, 222, 222); 
                    border: 1px solid rgb(220, 166, 166);
                    }
            </style>
            <p>Hora de saída deve ser maior que a hora de entrada.<p>";
        }

        $total_segundos = $hora_saida - $hora_entrada;
        $total = gmdate("H:i:s", $total_segundos);

        // Atualiza um único registro
        $sql = "UPDATE funcionarios_sede SET hora_entrada = '$this->hora_entrada', hora_saida = '$this->hora_saida', total_horas_dia = '$total'
                WHERE id = '$this->registro_id';";
        $objeto->set('sql', $sql);
        $objeto->query($sql);

        // Soma de todos os intervalos da semana
        $sql_total_horas = "SELECT SUM(TIME_TO_SEC(total_horas_dia)) AS total_segundos
                            FROM funcionarios_sede
                            WHERE funcionarios_id = '$this->funcionarios_id'
                            AND WEEK(data, 1) = '$semana'
                            AND YEAR(data) = '$ano';";
        $objeto->set('sql',$sql_total_horas);
        $resultado = $objeto->query($sql_total_horas);
        $linha = $resultado->fetch_assoc();

        $total_segundos = $linha['total_segundos'] ?? 0;
        $total_horas = gmdate("H:i:s", $total_segundos);

        // Verifica se já existe um registro semanal
        $verificaSQL = "SELECT id FROM totais_semanais
                        WHERE funcionarios_id = '$this->funcionarios_id'
                        AND semana = '$semana'
                        AND ano = '$ano';";
        $objeto->set('sql',$verificaSQL);
        $resultado2 = $objeto->query($verificaSQL);
        $linha2 = $resultado2->fetch_assoc();

        if (!$linha2) {
            $sql_insert = "INSERT INTO totais_semanais (funcionarios_id, semana, ano, total_horas)
                           VALUES ('$this->funcionarios_id', '$semana', '$ano', '$total_horas');";
            $objeto->set('sql',$sql_insert);
            $objeto->query($sql_insert);
        } else {
            $sql_update = "UPDATE totais_semanais SET total_horas = '$total_horas'
                           WHERE funcionarios_id = '$this->funcionarios_id'
                           AND semana = '$semana'
                           AND ano = '$ano';";
            $objeto->set('sql',$sql_update);
            $objeto->query($sql_update);
        }

        return "Registro alterado com sucesso.";
    }

    public function funcionarios_entrada() {
        $objeto = new Conexao();

        $data_atual = date("Y-m-d");

        // Verifica se há uma entrada sem saída
        $verificaSQL = "SELECT id FROM funcionarios_sede 
                        WHERE funcionarios_id = '$this->funcionarios_id' 
                        AND data = '$data_atual'
                        AND hora_entrada IS NOT NULL 
                        AND hora_saida IS NULL 
                        ORDER BY id DESC LIMIT 1;";
        $objeto->set("sql", $verificaSQL);
        $resultado = $objeto->query($verificaSQL);

        if ($resultado->num_rows > 0) {
            return "Você já registrou uma entrada e ainda não registrou a saída.";
        } else {
            // Insere nova entrada
            $sql_entrada = "INSERT INTO funcionarios_sede (funcionarios_id, data, hora_entrada) 
                            VALUES ('$this->funcionarios_id', '$data_atual', '$this->hora_entrada');";
            $objeto->set("sql", $sql_entrada);
            $objeto->query($sql_entrada);

            return "Entrada registrada com sucesso!";
        }
    }

    public function funcionarios_presenca_rg() {
        $objeto = new Conexao();

        $data_atual = date("Y-m-d");

        // Verifica se já tem entrada registrada
        $verificaSQL = "SELECT hora_entrada FROM funcionarios_rg 
                        WHERE funcionarios_id = '$this->funcionarios_id'
                        AND data = '$data_atual';";
        $objeto->set("sql", $verificaSQL);
        $resultado = $objeto->query($verificaSQL);
        $linha = $resultado->fetch_assoc();

        if (!$linha) {
            $SQL = "INSERT INTO funcionarios_rg (funcionarios_id, data, hora_entrada) 
                    VALUES ('$this->funcionarios_id', '$data_atual', '$this->hora_entrada');";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
            return "Você confirmou sua presença!";
        } 
        else {
            return "Presença já confirmada anteriormente.";
        }
    }

    public function funcionarios_presenca_evento() {
        $objeto = new Conexao();

        $data_atual = date("Y-m-d");

        // Verifica se já tem entrada registrada
        $verificaSQL = "SELECT hora_entrada FROM funcionarios_evento 
                        WHERE funcionarios_id = '$this->funcionarios_id'
                        AND evento_id = '$this->evento_id';";
        $objeto->set("sql", $verificaSQL);
        $resultado = $objeto->query($verificaSQL);
        $linha = $resultado->fetch_assoc();

        if (!$linha) {
            $SQL = "INSERT INTO funcionarios_evento (evento_id, funcionarios_id, data, hora_entrada) 
                    VALUES ('$this->evento_id', '$this->funcionarios_id', '$data_atual', '$this->hora_entrada');";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
            return "Você confirmou sua presença!";
        } 
        else {
            return "Presença já confirmada anteriormente.";
        }
    }

    public function funcionarios_saida() {
        $objeto = new Conexao();

        $data_atual = date("Y-m-d");
    
        // Busca última entrada sem saída
        $verificaSQL = "SELECT id, hora_entrada FROM funcionarios_sede 
                        WHERE funcionarios_id = '$this->funcionarios_id' 
                        AND data = '$data_atual'
                        AND hora_entrada IS NOT NULL 
                        AND hora_saida IS NULL 
                        ORDER BY id DESC LIMIT 1;";
        $objeto->set("sql", $verificaSQL);
        $resultado = $objeto->query($verificaSQL);
        $linha = $resultado->fetch_assoc();

        if (!$linha) {
            return "Você precisa registrar a entrada antes de registrar a saída.";
        }

        $id_registro = $linha['id'];
        $hora_entrada = strtotime($linha['hora_entrada']);
        $hora_saida = strtotime($this->hora_saida);

        if ($hora_saida <= $hora_entrada) {
            return "A hora de saída não pode ser anterior ou igual à hora de entrada.";
        } else {
            // Calcula intervalo
            $total_segundos = $hora_saida - $hora_entrada;
            $total = gmdate("H:i:s", $total_segundos);

            // Atualiza a saída e o total
            $sql_update = "UPDATE funcionarios_sede SET 
                        hora_saida = '$this->hora_saida', 
                        total_horas_dia = '$total' 
                        WHERE id = '$id_registro';";
            $objeto->set("sql", $sql_update);
            $objeto->query($sql_update);

            $this->totais_semanais();

            return "Você registrou a sua saída e o total de horas do dia!";
        }

    }

    public function totais_semanais() {
        $objeto = new Conexao();
        $data_atual = date("Y-m-d");

        // Determina a semana e o ano
        $semana = date("W", strtotime($data_atual)); // Semana do ano
        $ano = date("Y", strtotime($data_atual));    // Ano atual

        // Soma as horas totais da semana (segunda a sábado)
        $sql_total_horas = " SELECT SUM(TIME_TO_SEC(total_horas_dia)) AS total_segundos
                             FROM funcionarios_sede
                             WHERE funcionarios_id = '$this->funcionarios_id'
                             AND WEEK(data, 1) = '$semana'
                             AND YEAR(data) = '$ano'
                             AND WEEKDAY(data) < 7;";
        $objeto->set("sql", $sql_total_horas);
        $resultado = $objeto->query($sql_total_horas);
        $linha = $resultado->fetch_assoc();

        // Converte os segundos totais para o formato H:i:s
        $total_segundos = $linha['total_segundos'];
        $total_horas = gmdate("H:i:s", $total_segundos);

        // Verifica se já existe um registro de horas totais semanais
        $verificaSemanaSQL = "SELECT id FROM totais_semanais
                              WHERE funcionarios_id = '$this->funcionarios_id'
                              AND semana = '$semana'
                              AND ano = '$ano';";
        $objeto->set("sql", $verificaSemanaSQL);
        $resultado = $objeto->query($verificaSemanaSQL);
        $linha = $resultado->fetch_assoc();

        if (!$linha) {
            // Se não houver registro, insere um novo
            $SQL = "INSERT INTO totais_semanais (funcionarios_id, semana, ano, total_horas) 
                    VALUES ('$this->funcionarios_id', '$semana', '$ano', '$total_horas');";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
        } else {
            // Se já existir, atualiza o total de horas
            $SQL = "UPDATE totais_semanais SET 
                    total_horas = '$total_horas'
                    WHERE funcionarios_id = '$this->funcionarios_id'
                    AND semana = '$semana'
                    AND ano = '$ano';";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
        }
    }
}
?>
