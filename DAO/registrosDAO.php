<?php

date_default_timezone_set('America/Sao_Paulo');

class registrosDAO {
    public $id;
    public $funcionarios_id;
    public $hora_entrada;
    public $hora_saida;
    public $total_horas_dia;

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
            echo "Nome e/ou senha incorretos!";
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
        $semana = date("W", strtotime($this->data)); // Semana do ano
        $ano = date("Y", strtotime($this->data));    // Ano atual

        $hora_entrada = strtotime($this->hora_entrada);
        $hora_saida = strtotime($this->hora_saida);
        $total_segundos = $hora_saida - $hora_entrada;
        $total = gmdate("H:i:s", $total_segundos);


        $sql = "UPDATE funcionarios_sede SET 
                hora_entrada='$this->hora_entrada', 
                hora_saida='$this->hora_saida', 
                total_horas_dia = '$total'
                WHERE funcionarios_id ='$this->funcionarios_id' AND data = '$this->data'";
        $objeto->set("sql", $sql);
        $objeto->query($sql);
        
        $sql_total_horas = " SELECT SUM(TIME_TO_SEC(total_horas_dia)) AS total_segundos
                             FROM funcionarios_sede
                             WHERE funcionarios_id = '$this->funcionarios_id'
                             AND WEEK(data, 1) = '$semana'
                             AND YEAR(data) = '$ano'
                             AND WEEKDAY(data) < 7;";
        $objeto->set("sql", $sql_total_horas);
        $resultado = $objeto->query($sql_total_horas);
        $linha = $resultado->fetch_assoc();

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
            return "Alterado com Sucesso";
        }
        
    }
    
    public function funcionarios_entrada() {
        $objeto = new Conexao();

        $data_atual = date("Y-m-d");

        // Verifica se já tem entrada registrada
        $verificaSQL = "SELECT hora_entrada FROM funcionarios_sede 
                        WHERE funcionarios_id = '$this->funcionarios_id'
                        AND data = '$data_atual';";
        $objeto->set("sql", $verificaSQL);
        $resultado = $objeto->query($verificaSQL);
        $linha = $resultado->fetch_assoc();

        if (!$linha) {
            $SQL = "INSERT INTO funcionarios_sede (funcionarios_id, data, hora_entrada) 
                    VALUES ('$this->funcionarios_id', '$data_atual', '$this->hora_entrada');";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
            return "Você registrou a sua entrada!";
        } 
        else {
            return "Entrada já registrada anteriormente.";
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
    
        // Verifica se já tem entrada/registro existente
        $verificaSQL = "SELECT hora_entrada, hora_saida FROM funcionarios_sede 
                        WHERE funcionarios_id = '$this->funcionarios_id'
                        AND data = '$data_atual';";
        $objeto->set("sql", $verificaSQL);
        $resultado = $objeto->query($verificaSQL);
        $linha = $resultado->fetch_assoc();
      
        if (empty($linha['hora_entrada'])) {
            return "Você precisa registrar a entrada antes de registrar a saída.";
        }
        
        // Não permite registrar a saída duas vezes no mesmo dia
        if (!empty($linha['hora_saida'])) {
            return "Saída já registrada anteriormente.";
        }

        // Cálculo do total de horas
        $hora_entrada = strtotime($linha['hora_entrada']);
        $hora_saida = strtotime($this->hora_saida);
        $total_segundos = $hora_saida - $hora_entrada;
        $total = gmdate("H:i:s", $total_segundos);

        // Atualiza a saída e o total de horas
        $SQL = "UPDATE funcionarios_sede SET 
                hora_saida = '$this->hora_saida', 
                total_horas_dia = '$total'
                WHERE funcionarios_id = '$this->funcionarios_id'
                AND data = '$data_atual';";
        $objeto->set("sql", $SQL);
        $objeto->query();

        // Agora, vamos calcular as horas totais da semana
        $this->totais_semanais();

        return "Você registrou a sua saída e o total de horas do dia!";
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